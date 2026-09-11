<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'RHL Admin',
            'email' => 'admin@rhlproperties.com.bd',
            'password' => Hash::make('password'),
            // The roles themselves are created by the migration, not seeded, so
            // that production has them without seeders ever being run there.
            'role_id' => Role::where('is_admin', true)->value('id'),
        ]);
    }
}
