<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Settings\App\Models\Role;
use Modules\Settings\App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = Role::create([
            'name_en' => 'owner',
            'name_kh' => 'owner',
            'slug' => 'owner',
            'active' => 'Y',
        ])->id;
        User::create([
            'role_id' => $owner,
            'name' => 'owner',
            'username' => 'owner',
            'email' => 'vannethtak03@gmail.com',
            'phone' => '010296011',
            'password' => Hash::make('vannethtak03'),
            'active' => 'Y',
            'avatar' => 'no-avatar.png',
            'locale' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
