<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory()->create([
             'name' => 'Admin',
             'email' => 'admin@outlook.com',
             'password' => Hash::make('43wqD2@sl1'),
             'approve' => User::SUPERADMIN,
             'is_active' => true
         ]);

         Currency::create([
             'name' => 'USD $',
             'is_active' => true
         ]);
         Currency::create([
             'name' => 'GBP £',
             'is_active' => true
         ]);
         Currency::create([
             'name' => 'EUR €',
             'is_active' => true
         ]);
    }
}
