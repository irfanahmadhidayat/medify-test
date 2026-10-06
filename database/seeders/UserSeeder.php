<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's default user.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            ['email' => '123@123'],
            [
                'name' => '123',
                'password' => Hash::make('123'),
            ]
        );
    }
}