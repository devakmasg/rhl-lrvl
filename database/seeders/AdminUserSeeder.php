<?php

namespace Database\Seeders;

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
            'role' => 'Administrator',
        ]);
    }
}
