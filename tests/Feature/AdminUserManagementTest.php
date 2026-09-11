<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Covers the rules that keep the client out of trouble: who may reach the
 * admin-only areas, and the guards that make it impossible to click your way
 * into a site with no administrator left.
 */
class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(array $attributes = []): User
    {
        return User::factory()->create($attributes + [
            'role_id' => Role::where('is_admin', true)->value('id'),
            'is_active' => true,
        ]);
    }

    private function editor(array $attributes = []): User
    {
        return User::factory()->create($attributes + [
            'role_id' => Role::where('slug', 'content-editor')->value('id'),
            'is_active' => true,
        ]);
    }

    public function test_editor_cannot_reach_users_or_settings(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->get('/admin/users')->assertForbidden();
        $this->actingAs($editor)->get('/admin/settings')->assertForbidden();
    }

    public function test_editor_can_still_reach_content(): void
    {
        $this->actingAs($this->editor())->get('/admin/projects')->assertOk();
    }

    public function test_admin_can_reach_users(): void
    {
        $admin = $this->admin(['name' => 'Ada Admin']);
        // A second row so the per-user action buttons and their modals actually
        // render — they are hidden on your own row.
        $this->editor(['name' => 'Eddie Editor', 'email' => 'eddie@example.com']);
        $this->editor(['name' => 'Off Editor', 'email' => 'off@example.com', 'is_active' => false]);

        $this->actingAs($admin)->get('/admin/users')
            ->assertOk()
            ->assertSee('Ada Admin')
            ->assertSee('Eddie Editor')
            ->assertSee('eddie@example.com')
            ->assertSee('Administrator')
            ->assertSee('Editor')
            ->assertSee('Active')
            ->assertSee('Inactive')
            ->assertSee('Deactivate')
            ->assertSee('Reset Password');
    }

    public function test_admin_creates_a_user_with_a_temporary_password(): void
    {
        $this->actingAs($this->admin())->post('/admin/users', [
            'name' => 'New Editor',
            'email' => 'new@example.com',
            'role_id' => Role::where('slug', 'content-editor')->value('id'),
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'is_active' => '1',
        ])->assertRedirect('/admin/users');

        $created = User::where('email', 'new@example.com')->first();

        $this->assertNotNull($created);
        $this->assertSame('content-editor', $created->role->slug);
        $this->assertTrue($created->is_active);
        $this->assertTrue($created->must_change_password);
        $this->assertTrue(Hash::check('secret-password', $created->password));
    }

    public function test_deactivated_user_cannot_log_in(): void
    {
        $user = $this->editor(['email' => 'off@example.com', 'is_active' => false]);

        $this->post('/admin/login', [
            'email' => 'off@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_deactivating_a_user_ends_their_open_session(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->get('/admin')->assertOk();

        $editor->update(['is_active' => false]);

        $this->actingAs($editor)->get('/admin')->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_temporary_password_blocks_the_rest_of_the_panel(): void
    {
        $editor = $this->editor(['must_change_password' => true]);

        $this->actingAs($editor)->get('/admin')->assertRedirect(route('admin.password.change'));
        $this->actingAs($editor)->get('/admin/projects')->assertRedirect(route('admin.password.change'));
        $this->actingAs($editor)->get('/admin/password/change')->assertOk();
    }

    public function test_choosing_a_password_clears_the_flag(): void
    {
        $editor = $this->editor(['must_change_password' => true]);

        $this->actingAs($editor)->put('/admin/password/change', [
            'password' => 'my-own-password',
            'password_confirmation' => 'my-own-password',
        ])->assertRedirect(route('admin.dashboard'));

        $editor->refresh();

        $this->assertFalse($editor->must_change_password);
        $this->assertTrue(Hash::check('my-own-password', $editor->password));
    }

    public function test_the_new_password_cannot_be_the_temporary_one(): void
    {
        $editor = $this->editor(['must_change_password' => true]);

        $this->actingAs($editor)->put('/admin/password/change', [
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('password');

        $this->assertTrue($editor->fresh()->must_change_password);
    }

    public function test_admin_reset_forces_the_user_to_choose_again(): void
    {
        $editor = $this->editor();

        $this->actingAs($this->admin())->put('/admin/users/'.$editor->id.'/password', [
            'password' => 'reset-password',
            'password_confirmation' => 'reset-password',
        ])->assertRedirect('/admin/users');

        $editor->refresh();

        $this->assertTrue($editor->must_change_password);
        $this->assertTrue(Hash::check('reset-password', $editor->password));
    }

    public function test_you_cannot_deactivate_yourself(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/users/'.$admin->id.'/toggle')
            ->assertSessionHas('error');

        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_you_cannot_delete_yourself(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->delete('/admin/users/'.$admin->id)
            ->assertSessionHas('error');

        $this->assertNotNull($admin->fresh());
    }

    public function test_you_cannot_change_your_own_role(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put('/admin/users/'.$admin->id, [
            'name' => $admin->name,
            'email' => $admin->email,
            'role_id' => Role::where('slug', 'content-editor')->value('id'),
        ])->assertSessionHas('error');

        $this->assertTrue($admin->fresh()->isAdmin());
    }

    public function test_an_admin_can_demote_another_admin(): void
    {
        $admin = $this->admin();
        $other = $this->admin();

        $this->actingAs($other)->put('/admin/users/'.$admin->id, [
            'name' => $admin->name,
            'email' => $admin->email,
            'role_id' => Role::where('slug', 'content-editor')->value('id'),
        ])->assertRedirect('/admin/users');

        // Safe precisely because the one doing it stays an administrator.
        $this->assertSame('content-editor', $admin->fresh()->role->slug);
        $this->assertTrue($other->fresh()->isAdmin());
    }

    /**
     * The property that actually matters: no sequence of clicks empties the site
     * of active administrators. It holds because nobody can act on their own row
     * — whoever is doing the damage is always an active administrator who
     * survives it.
     */
    public function test_an_active_administrator_always_survives(): void
    {
        $admin = $this->admin();
        $other = $this->admin();
        $this->editor();

        // Strip the account down to one administrator...
        $this->actingAs($admin)->delete('/admin/users/'.$other->id)->assertRedirect('/admin/users');
        $this->assertNull(User::find($other->id));

        // ...then try every way of removing the one that is left.
        $this->actingAs($admin)->post('/admin/users/'.$admin->id.'/toggle')->assertSessionHas('error');
        $this->actingAs($admin)->delete('/admin/users/'.$admin->id)->assertSessionHas('error');
        $this->actingAs($admin)->put('/admin/users/'.$admin->id, [
            'name' => $admin->name,
            'email' => $admin->email,
            'role_id' => Role::where('slug', 'content-editor')->value('id'),
        ])->assertSessionHas('error');

        $this->assertSame(1, User::whereHas('role', fn ($q) => $q->where('is_admin', true))->where('is_active', true)->count());
    }

    public function test_a_deactivated_admin_can_no_longer_manage_users(): void
    {
        $admin = $this->admin();
        $other = $this->admin(['is_active' => false]);

        $this->actingAs($other)->delete('/admin/users/'.$admin->id)
            ->assertRedirect(route('admin.login'));

        $this->assertNotNull(User::find($admin->id));
    }
}
