<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Turns `role` from a free-text sidebar label into a real permission value.
     *
     * Before this, every user row was a full administrator and `role` only ever
     * decided what text sat under their name in the sidebar. The live rows all
     * hold 'Administrator', so they map to 'admin' and nobody loses access.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('role');
            $table->boolean('must_change_password')->default(false)->after('is_active');
        });

        DB::table('users')->whereNotIn('role', ['admin', 'editor'])->update(['role' => 'admin']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('editor')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'must_change_password']);
            $table->string('role')->default('Administrator')->change();
        });

        DB::table('users')->where('role', 'admin')->update(['role' => 'Administrator']);
        DB::table('users')->where('role', 'editor')->update(['role' => 'Editor']);
    }
};
