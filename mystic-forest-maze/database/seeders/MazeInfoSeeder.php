<?php

namespace Database\Seeders;

use App\Models\MazeInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;


class MazeInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $localImagePath = './public/forest.png';
        $destinationPath = 'images';
        $filename = uniqid() . '.png';
        Storage::putFileAs($destinationPath, $localImagePath, $filename);

        MazeInfo::create([
            "logo" => $destinationPath . '/' . $filename,
            "bg-color"=> "#0693e3",
            "font"=>"Helvetica",
            "administration_id"=>1
        ]);
    }
}
