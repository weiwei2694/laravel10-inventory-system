<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{User, Order};
use DateTime;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 2)->first();
        Order::create([
            'date' => new DateTime(),
            'customer_name' => 'test',
            'customer_email' => 'test@gmail.com',
            'user_id' => $admin->id
        ]);
    }
}
