<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Code;
use App\Models\Result;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         Code::factory(6)->create();
         Result::factory(10)->create();
       $this->call([
        AdministrationSeeder::class,
        MazeInfoSeeder::class,
       ]);
    }
}


