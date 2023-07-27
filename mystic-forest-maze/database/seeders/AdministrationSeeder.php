<?php

namespace Database\Seeders;

use App\Models\Administration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Administration::create([
            "name" => "Maze Administration",
            "email" => "maze_administration@gmail.com",
            "password" => Hash::make("12345678")
        ]);
    }
}
