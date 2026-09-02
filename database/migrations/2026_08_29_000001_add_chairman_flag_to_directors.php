<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The Chairman's Message page needs to know who the Chairman is, the same
     * way the MD Message page knows who the Managing Director is.
     *
     * A flag rather than a role match, for the reason the MD flag exists:
     * "Chairman & Founder" is a perfectly reasonable thing to type into the
     * role field, and it must not empty a page when someone does.
     */
    public function up(): void
    {
        Schema::table('directors', function (Blueprint $table) {
            $table->boolean('is_chairman')->default(false)->after('is_managing_director');
        });

        // Carry over whoever is already sitting in the role, so an existing
        // site gets a working page without anyone touching admin first.
        $current = DB::table('directors')
            ->where('role', 'like', 'Chairman%')
            ->orderBy('order')
            ->first();

        if ($current) {
            DB::table('directors')->where('id', $current->id)->update(['is_chairman' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('directors', function (Blueprint $table) {
            $table->dropColumn('is_chairman');
        });
    }
};
