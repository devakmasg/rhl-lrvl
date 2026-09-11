<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phone numbers the site can show, each with the name of the desk it
     * reaches — "Head Office", "Sales", "Hotline".
     *
     * Before this there was one `settings.phone`, printed in ten places. Two
     * of those places wanted a number this table can now hold separately: the
     * Sales Team page's "call us" line, and the Partners page's "Partnership
     * desk" — both settled for the office number because it was the only one
     * that existed.
     *
     * `settings.phone` is deliberately left in place. ContactNumber::primary()
     * falls back to it, so the site keeps rendering a number between this
     * migration running and the first row being created.
     */
    public function up(): void
    {
        Schema::create('contact_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('number');

            // The slot a template asks for by name — see ContactNumber::SLOTS.
            // Nullable because most numbers are just numbers; unique because a
            // slot names one desk, and forSlot() returns a single row.
            $table->string('key')->nullable()->unique();

            // Exactly one row carries this: the number every single-number
            // spot on the site uses (the floating call button, {phone}, the
            // tel: CTA buttons). The controller clears it from the others.
            $table->boolean('is_primary')->default(false);

            // The footer column only has room for two or three lines before
            // the layout suffers, so the list it shows is opt-in per number.
            $table->boolean('show_in_footer')->default(true);

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Carry the existing number over as the primary, so the live site
        // reads identically before anyone opens the new screen.
        $phone = Schema::hasTable('settings')
            ? DB::table('settings')->value('phone')
            : null;

        if (filled($phone)) {
            DB::table('contact_numbers')->insert([
                'label' => 'Head Office',
                'number' => $phone,
                'key' => null,
                'is_primary' => true,
                'show_in_footer' => true,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_numbers');
    }
};
