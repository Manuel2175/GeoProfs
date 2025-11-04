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
            'name' => 'user1',
            'surname' => 'user2',
            'password' => Hash::make('pass1'),
        ]);
    }
}
