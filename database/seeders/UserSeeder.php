<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        # role user
        User::create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'role' => 1,
        ]);

        # role admin
        User::create([
            'name' => 'test2',
            'email' => 'test2@gmail.com',
            'password' => 'password',
            'role' => 2,
        ]);
    }
}
