<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\Role;
use App\Models\User;
use App\Support\AdminSections;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Covers role-based section permissions: that they are enforced on the routes
 * and not merely hidden in the menu, that the registry stays consistent with
 * the routes it claims to cover, and that the Roles screen cannot be used to
 * lock the client out.
 */
class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug, array $attributes = []): User
    {
        return User::factory()->create($attributes + [
            'role_id' => Role::where('slug', $slug)->value('id'),
            'is_active' => true,
        ]);
    }

    /**
     * Admin routes that are deliberately outside the permission system: signing
     * in and out, your own profile, the forced password change, and the
     * administrator-only Users and Roles screens.
     */
    private function unsectionedByDesign(): array
    {
        return [
            'admin.login', 'admin.login.attempt', 'admin.logout',
            'admin.password.change', 'admin.password.change.update',
            'admin.profile.edit', 'admin.profile.update', 'admin.profile.password',
            'admin.users.*', 'admin.roles.*',
        ];
    }

    // ---------------------------------------------------------------- presets

    public function test_sales_reaches_only_the_lead_pipeline(): void
    {
        $sales = $this->userWithRole('sales');

        $this->actingAs($sales)->get('/admin')->assertOk();
        $this->actingAs($sales)->get('/admin/inquiries')->assertOk();

        $this->actingAs($sales)->get('/admin/projects')->assertForbidden();
        $this->actingAs($sales)->get('/admin/news')->assertForbidden();
        $this->actingAs($sales)->get('/admin/settings')->assertForbidden();
        $this->actingAs($sales)->get('/admin/media')->assertForbidden();
    }

    public function test_content_editor_reaches_content_but_not_inquiries_or_settings(): void
    {
        $editor = $this->userWithRole('content-editor');

        $this->actingAs($editor)->get('/admin/projects')->assertOk();
        $this->actingAs($editor)->get('/admin/news')->assertOk();
        $this->actingAs($editor)->get('/admin/media')->assertOk();

        $this->actingAs($editor)->get('/admin/inquiries')->assertForbidden();
        $this->actingAs($editor)->get('/admin/settings')->assertForbidden();
    }

    public function test_manager_reaches_everything_except_users_and_roles(): void
    {
        // The settings screen reads the singleton row the app always ships with.
        $this->seed(SettingSeeder::class);
        $manager = $this->userWithRole('manager');

        $this->actingAs($manager)->get('/admin/settings')->assertOk();
        $this->actingAs($manager)->get('/admin/inquiries')->assertOk();
        $this->actingAs($manager)->get('/admin/projects')->assertOk();

        $this->actingAs($manager)->get('/admin/users')->assertForbidden();
        $this->actingAs($manager)->get('/admin/roles')->assertForbidden();
    }

    public function test_administrator_reaches_everything(): void
    {
        $this->seed(SettingSeeder::class);
        $admin = $this->userWithRole('administrator');

        foreach (['/admin', '/admin/inquiries', '/admin/projects', '/admin/settings', '/admin/users', '/admin/roles'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    // ------------------------------------------------- enforcement, not just UI

    public function test_a_denied_section_is_refused_on_its_sub_routes_too(): void
    {
        $sales = $this->userWithRole('sales');

        // Not just the index the menu links to — the whole section.
        $this->actingAs($sales)->get('/admin/projects/create')->assertForbidden();
        $this->actingAs($sales)->post('/admin/projects')->assertForbidden();
        $this->actingAs($sales)->get('/admin/page-headers')->assertForbidden();
    }

    public function test_the_sidebar_only_lists_granted_sections(): void
    {
        $sales = $this->userWithRole('sales');

        $response = $this->actingAs($sales)->get('/admin');

        $response->assertSee('Inquiries');
        $response->assertDontSee('Media Library');
        $response->assertDontSee('Site Settings');
        $response->assertDontSee('Leaders &amp; Team', false);
        // Users and Roles are administrator-only regardless of any role's list.
        $response->assertDontSee('Roles &amp; Permissions', false);
    }

    public function test_the_dashboard_hides_inquiry_data_from_roles_without_it(): void
    {
        Inquiry::create([
            'reference' => 'INQ-TEST-1',
            'type' => 'project',
            'name' => 'Rashida Karim',
            'phone' => '01700000000',
            'email' => 'rashida@example.com',
            'message' => 'Interested in a plot.',
            'status' => 'new',
        ]);

        // The dashboard is the one page that renders another section's data.
        $this->actingAs($this->userWithRole('content-editor'))->get('/admin')
            ->assertOk()
            ->assertDontSee('Rashida Karim')
            ->assertDontSee('01700000000')
            ->assertDontSee('Recent Inquiries');

        $this->actingAs($this->userWithRole('sales'))->get('/admin')
            ->assertOk()
            ->assertSee('Rashida Karim')
            ->assertSee('Recent Inquiries');
    }

    public function test_changing_a_role_takes_effect_immediately(): void
    {
        $editor = $this->userWithRole('content-editor');

        $this->actingAs($editor)->get('/admin/news')->assertOk();

        $role = Role::where('slug', 'content-editor')->first();
        $role->update(['permissions' => array_values(array_diff($role->permissions, ['news']))]);

        $this->actingAs($editor->fresh())->get('/admin/news')->assertForbidden();
    }

    // ------------------------------------------------------- registry integrity

    public function test_every_section_points_at_a_real_route_that_maps_back_to_it(): void
    {
        foreach (AdminSections::all() as $key => $section) {
            $this->assertTrue(
                Route::has($section['route']),
                "Section [{$key}] links to route [{$section['route']}], which does not exist."
            );

            $this->assertSame(
                $key,
                AdminSections::forRouteName($section['route']),
                "Section [{$key}] links to a route that resolves to a different section."
            );
        }
    }

    /**
     * The guard against a future admin page shipping with no permission at all.
     * Unmatched routes are allowed through by design (profile, logout), so a new
     * section added without a registry entry would silently be readable by
     * everybody. This fails the moment that happens.
     */
    public function test_every_admin_route_is_either_in_a_section_or_deliberately_exempt(): void
    {
        $unregistered = [];

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();

            if ($name === null || ! Str::startsWith($name, 'admin.')) {
                continue;
            }

            if (Str::is($this->unsectionedByDesign(), $name)) {
                continue;
            }

            if (AdminSections::forRouteName($name) === null) {
                $unregistered[] = $name;
            }
        }

        $this->assertSame([], $unregistered, 'These admin routes belong to no section, so every role can reach them: '.implode(', ', $unregistered));
    }

    // ------------------------------------------------------------- roles screen

    public function test_only_administrators_manage_roles(): void
    {
        $this->actingAs($this->userWithRole('manager'))->get('/admin/roles')->assertForbidden();
        $this->actingAs($this->userWithRole('administrator'))->get('/admin/roles')->assertOk();
    }

    public function test_an_administrator_creates_a_role_with_chosen_sections(): void
    {
        $this->actingAs($this->userWithRole('administrator'))->post('/admin/roles', [
            'name' => 'Newsroom',
            'description' => 'Press releases only',
            'permissions' => ['dashboard', 'news'],
        ])->assertRedirect('/admin/roles');

        $role = Role::where('name', 'Newsroom')->first();

        $this->assertNotNull($role);
        $this->assertSame('newsroom', $role->slug);
        $this->assertSame(['dashboard', 'news'], $role->permissions);
        $this->assertFalse($role->is_admin);

        $member = $this->userWithRole('newsroom');
        $this->actingAs($member)->get('/admin/news')->assertOk();
        $this->actingAs($member)->get('/admin/projects')->assertForbidden();
    }

    public function test_unknown_permission_keys_are_rejected(): void
    {
        $this->actingAs($this->userWithRole('administrator'))->post('/admin/roles', [
            'name' => 'Bogus',
            'permissions' => ['news', 'not-a-real-section'],
        ])->assertSessionHasErrors('permissions.1');

        $this->assertNull(Role::where('name', 'Bogus')->first());
    }

    public function test_a_role_with_no_sections_can_sign_in_but_reach_nothing(): void
    {
        $admin = $this->userWithRole('administrator');

        $this->actingAs($admin)->post('/admin/roles', ['name' => 'Empty', 'permissions' => []]);

        $stranded = $this->userWithRole('empty');

        $this->actingAs($stranded)->get('/admin')->assertForbidden();
        // Their own profile stays reachable, so they are never trapped on a 403.
        $this->actingAs($stranded)->get('/admin/profile')->assertOk();
    }

    public function test_the_administrator_role_cannot_be_edited_or_deleted(): void
    {
        $admin = $this->userWithRole('administrator');
        $role = Role::where('is_admin', true)->first();

        $this->actingAs($admin)->put('/admin/roles/'.$role->id, [
            'name' => 'Weakened',
            'permissions' => ['news'],
        ])->assertSessionHas('error');

        $this->actingAs($admin)->delete('/admin/roles/'.$role->id)->assertSessionHas('error');

        $role->refresh();

        $this->assertSame('Administrator', $role->name);
        $this->assertTrue($role->is_admin);
        $this->assertTrue($role->allows('settings'));
    }

    public function test_a_role_still_in_use_cannot_be_deleted(): void
    {
        $this->userWithRole('sales');
        $role = Role::where('slug', 'sales')->first();

        $this->actingAs($this->userWithRole('administrator'))
            ->delete('/admin/roles/'.$role->id)
            ->assertSessionHas('error');

        $this->assertNotNull(Role::find($role->id));
    }

    public function test_an_unused_role_can_be_deleted(): void
    {
        $role = Role::where('slug', 'sales')->first();

        $this->actingAs($this->userWithRole('administrator'))
            ->delete('/admin/roles/'.$role->id)
            ->assertRedirect('/admin/roles');

        $this->assertNull(Role::find($role->id));
    }
}
