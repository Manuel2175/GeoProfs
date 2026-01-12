<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //generate workers and admins
        User::factory(10)->create();
        User::create([
            'name' => 'user',
            'surname' => 'user',
            'password' => Hash::make('pass'),
        ]);
        User::create([
                    'name' => 'userAdmin',
                    'surname' => 'user',
                    'role' => 'admin',
                    'password' => Hash::make('pass'),
                ]);
    }
}
