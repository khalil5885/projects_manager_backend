<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'khalil admin',
            'email'=>'admin231@gmail.com',
            'password'=>Hash::make('khalil123'),
            'role'=>'admin'
        ]);
    }
}
