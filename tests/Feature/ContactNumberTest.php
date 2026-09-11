<?php

namespace Tests\Feature;

use App\Models\ContactNumber;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Support\Tokens;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Covers the two invariants the Contact Numbers screen exists to keep, and the
 * lookups the site depends on.
 *
 * The site prints a number in eleven places and only two of them can show a
 * list; the other nine read primary() or forSlot(). Both must always return a
 * row, or a call button renders with an empty href — which is why "never zero
 * primaries" is enforced in the controller and asserted here.
 */
class ContactNumberTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'role_id' => Role::where('slug', 'administrator')->value('id'),
            'is_active' => true,
        ]);
    }

    private function number(array $attributes = []): ContactNumber
    {
        return ContactNumber::create($attributes + [
            'label' => 'Head Office',
            'number' => '+880 1711-234567',
            'is_primary' => false,
            'show_in_footer' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_the_screen_lists_numbers(): void
    {
        $this->number(['label' => 'Sales desk', 'is_primary' => true]);

        $this->actingAs($this->admin())
            ->get(route('admin.contact-numbers.index'))
            ->assertOk()
            ->assertSee('Sales desk');
    }

    public function test_marking_one_number_primary_clears_it_from_the_others(): void
    {
        $first = $this->number(['is_primary' => true]);
        $second = $this->number(['label' => 'Sales', 'number' => '+880 1999-111222', 'sort_order' => 2]);

        $this->actingAs($this->admin())
            ->put(route('admin.contact-numbers.update', $second), [
                'label' => 'Sales',
                'number' => '+880 1999-111222',
                'is_primary' => '1',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->assertTrue($second->fresh()->is_primary);
        $this->assertFalse($first->fresh()->is_primary);
    }

    public function test_unticking_the_last_primary_puts_it_back(): void
    {
        $only = $this->number(['is_primary' => true]);

        $this->actingAs($this->admin())
            ->put(route('admin.contact-numbers.update', $only), [
                'label' => 'Head Office',
                'number' => '+880 1711-234567',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->assertTrue($only->fresh()->is_primary);
    }

    public function test_deleting_the_primary_promotes_the_next_number(): void
    {
        $primary = $this->number(['is_primary' => true]);
        $other = $this->number(['label' => 'Sales', 'number' => '+880 1999-111222', 'sort_order' => 2]);

        $this->actingAs($this->admin())
            ->delete(route('admin.contact-numbers.destroy', $primary))
            ->assertRedirect();

        $this->assertTrue($other->fresh()->is_primary);
    }

    public function test_the_first_number_added_becomes_the_primary_unasked(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.contact-numbers.store'), [
                'label' => 'Head Office',
                'number' => '+880 1711-234567',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->assertTrue(ContactNumber::first()->is_primary);
    }

    public function test_a_desk_can_hold_only_one_number(): void
    {
        $this->number(['key' => 'sales', 'is_primary' => true]);

        $this->actingAs($this->admin())
            ->post(route('admin.contact-numbers.store'), [
                'label' => 'Another sales line',
                'number' => '+880 1999-111222',
                'key' => 'sales',
                'is_active' => '1',
            ])
            ->assertSessionHasErrors('key');
    }

    public function test_a_desk_with_no_number_falls_back_to_the_primary(): void
    {
        $primary = $this->number(['is_primary' => true]);

        $this->assertSame($primary->id, ContactNumber::forSlot('sales')->id);
    }

    public function test_a_desk_with_its_own_number_wins_over_the_primary(): void
    {
        $this->number(['is_primary' => true]);
        $sales = $this->number(['label' => 'Sales', 'number' => '+880 1999-111222', 'key' => 'sales', 'sort_order' => 2]);

        $this->assertSame($sales->id, ContactNumber::forSlot('sales')->id);
    }

    public function test_hidden_numbers_are_never_shown(): void
    {
        $this->number(['is_primary' => true]);
        $this->number(['label' => 'Old line', 'number' => '+880 1555-000000', 'is_active' => false, 'sort_order' => 2]);

        $this->assertCount(1, ContactNumber::live());
    }

    public function test_the_footer_list_only_holds_the_numbers_ticked_for_it(): void
    {
        $this->number(['is_primary' => true]);
        $this->number(['label' => 'Sales', 'number' => '+880 1999-111222', 'show_in_footer' => false, 'sort_order' => 2]);

        $this->assertCount(2, ContactNumber::live());
        $this->assertCount(1, ContactNumber::footerList());
    }

    public function test_the_tel_href_keeps_only_digits_and_a_leading_plus(): void
    {
        $this->assertSame('+8801711234567', $this->number()->tel);
    }

    /**
     * Site Settings no longer carries a Phone field. It used to be a required
     * rule, so the form would 422 on every save if the rule outlived the input.
     */
    public function test_site_settings_still_saves_without_a_phone_field(): void
    {
        $this->seed(SettingSeeder::class);
        $setting = Setting::first();

        $this->actingAs($this->admin())
            ->put(route('admin.settings.update'), [
                'site_name' => $setting->site_name,
                'brand_mark' => $setting->brand_mark,
                'address' => 'House 99, Road 1, Banani, Dhaka',
                'whatsapp' => $setting->whatsapp,
                'email' => $setting->email,
                'hours_weekday' => $setting->hours_weekday,
                'hours_saturday' => $setting->hours_saturday,
                'hours_friday' => $setting->hours_friday,
                'map_query' => $setting->map_query,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertSame('House 99, Road 1, Banani, Dhaka', $setting->fresh()->address);
        // The column is untouched by the form — it is the fallback, not a field.
        $this->assertSame('+880 1812-345678', $setting->fresh()->phone);
    }

    /**
     * The fallback that lets this ship without a content edit: with no rows at
     * all, primary() still answers with the old settings.phone.
     */
    public function test_with_no_numbers_at_all_the_settings_phone_is_used(): void
    {
        $this->seed(SettingSeeder::class);

        $this->assertSame(0, ContactNumber::count());
        $this->assertNotNull(ContactNumber::primary());
        $this->assertNotSame('', ContactNumber::primary()->number);
        $this->assertStringContainsString(ContactNumber::primary()->number, Tokens::expand('Call {phone}'));
    }
}
