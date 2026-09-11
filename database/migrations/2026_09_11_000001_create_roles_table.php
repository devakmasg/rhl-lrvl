<?php

use App\Support\AdminSections;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Replaces the fixed admin/editor pair with named roles the client can
     * manage themselves, each carrying the list of admin sections it may open.
     *
     * The four presets are created here rather than in a seeder so that a fresh
     * deploy has working roles the moment it migrates — seeders are not run on
     * production.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            // Administrators bypass the section checks entirely and are the only
            // ones who reach Users and Roles. Kept as a flag rather than a magic
            // slug so the client can rename the role without breaking access.
            $table->boolean('is_admin')->default(false);
            // Blocks deletion of the roles the panel assumes exist.
            $table->boolean('is_system')->default(false);
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        $now = now();
        $all = AdminSections::keys();

        // Everything a Content Editor should not touch: customer data and the
        // company-wide settings.
        $editor = array_values(array_diff($all, ['inquiries', 'settings']));

        DB::table('roles')->insert([
            [
                'name' => 'Administrator',
                'slug' => 'administrator',
                'description' => 'Full access, including users and roles.',
                'is_admin' => true,
                'is_system' => true,
                'permissions' => json_encode($all),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Everything except users and roles.',
                'is_admin' => false,
                'is_system' => false,
                'permissions' => json_encode($all),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Content Editor',
                'slug' => 'content-editor',
                'description' => 'Website content only — no inquiries, no site settings.',
                'is_admin' => false,
                'is_system' => false,
                'permissions' => json_encode($editor),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Sales',
                'slug' => 'sales',
                'description' => 'The lead pipeline — dashboard and inquiries.',
                'is_admin' => false,
                'is_system' => false,
                'permissions' => json_encode(['dashboard', 'inquiries']),
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);

        $administrator = DB::table('roles')->where('slug', 'administrator')->value('id');
        $contentEditor = DB::table('roles')->where('slug', 'content-editor')->value('id');

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('avatar')->constrained('roles');
        });

        // The two values the previous migration left behind map straight across.
        DB::table('users')->where('role', 'admin')->update(['role_id' => $administrator]);
        DB::table('users')->where('role', 'editor')->update(['role_id' => $contentEditor]);
        // Anything unrecognised is treated as the least privileged option rather
        // than being left with no role at all.
        DB::table('users')->whereNull('role_id')->update(['role_id' => $contentEditor]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('editor')->after('avatar');
        });

        $adminRoleIds = DB::table('roles')->where('is_admin', true)->pluck('id')->all();

        DB::table('users')->whereIn('role_id', $adminRoleIds)->update(['role' => 'admin']);
        DB::table('users')->whereNotIn('role_id', $adminRoleIds)->update(['role' => 'editor']);

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        Schema::dropIfExists('roles');
    }
};
