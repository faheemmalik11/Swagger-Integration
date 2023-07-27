<?php

namespace App\Http\Controllers;

use App\Models\MazeInfo;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MazeInfoController extends Controller
{
    public function update(){
        $validated =request()->validate([
            'font'=>'required|string',
            "bg-color"=>"required|string",
            "logo"=>"required"
        ]);

        try{
            $maze_info = Auth()->user()->maze_info;
            $localImagePath = request('logo');
            $destinationPath = 'images';
            $filename = uniqid() . '.png';
            Storage::putFileAs($destinationPath, $localImagePath, $filename);
            $validated['logo'] = $destinationPath . '/' . $filename;
            $maze_info->update($validated);

        }catch(Exception $e){
            return response()->json([
                'code' =>409,
                'message' =>'Maze Info cannot be updated'
            ]);
        }

        return response()->json([
            'code' =>200,
            'data' =>[
                'message' =>'Maze Info updated successfully',
                'maze_info' =>$maze_info
            ]
        ]);
    }


    public function maze_info(){
        try{
            $maze_info = Auth()->user()->maze_info;
        }catch(Exception $e){
            return response()->json([
                'code' =>409,
                'message' =>'maze info cannot be fetched'
            ]);
        }

        if($maze_info == null){
            return response()->json([
                'code' =>200,
                'message' =>'no records found'
            ]);
        }
        return response()->json([
            'code' =>200,
            'data' =>[
                'message' => 'maze info fetched successfully',
                'maze_info' => $maze_info
            ]
            ]);
    }
}
