<?php

namespace App\Http\Controllers;

use App\Models\MazeInfo;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MazeInfoController extends Controller
{

    /**
        * @OA\Put(
        * path="/maze_info/update",
        * operationId="maze_infoUpdate",
        * tags={"Maze Info"},
        * summary="Maze Info Update",
        * description="maze_info Updation Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"logo","bg-color","font"},
        *               @OA\Property(property="logo", type="string"),
        *               @OA\Property(property="bg-color", type="string"),       
        *               @OA\Property(property="font", type="string"),  
        *            ),
        *        ),
        *    ),
        *      
        *      @OA\Response(
        *          response=200,
        *          description="Maze Info Update Successfully",
        *          @OA\JsonContent(            
        *               example={
        *            "code": 200,
        *            "data": {
        *                "message": "Maze Info updated successfully",
        *                "maze_info": {
        *                    "id": 1,
        *                    "logo": "images/64c127d67ae1c.png",
        *                    "bg-color": "#3891CB",
        *                    "font": "Arial",
        *                    "administration_id": 1,
        *                    "created_at": "2023-07-25T14:33:21.000000Z",
        *                    "updated_at": "2023-07-26T14:04:06.000000Z"
        *                }
        *            }
        *        }),
        *       ),
 
        *      @OA\Response(
        *          response=400,
        *          description="Bad Request",
        *          @OA\JsonContent(            
        *               example={
        *                   "error": "Invalid token",
        *                   "message": "The provided token is not in the expected format or is malformed."
        *               }
        *       ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Resource not found",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": "404",
        *                   "message": "nmaze info not found",
        *               }
        *       ),
        *       ),
        *       @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": "422",
        *                   "message": "The maze_info could not be updated. Please check the provided data and try again.",
        *               }
        *       ),
        *       ),
        *     security={{"bearer_token":{}}}
        * )
        */
    public function update(){
        $validated =request()->validate([
            'font'=>'required|string',
            "bg-color"=>"required|string",
            "logo"=>"required"
        ]);

        try {
            $maze_info = Auth()->user()->maze_info;
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 404,
                'message' => 'maze info not found'
            ],404);
        }
        try{
            
            $localImagePath = request('logo');
            $destinationPath = 'images';
            $filename = uniqid() . '.png';
            Storage::putFileAs($destinationPath, $localImagePath, $filename);
            $validated['logo'] = $destinationPath . '/' . $filename;
            $maze_info->update($validated);

        }catch(Exception $e){
            return response()->json([
                'code' =>422,
                'message' =>"The maze_info could not be updated. Please check the provided data and try again.",
            ],422);
        }

        return response()->json([
            'code' =>200,
            'data' =>[
                'message' =>'Maze Info updated successfully',
                'maze_info' =>$maze_info
            ]
        ]);
    }


   /**
        * @OA\Get(
        * path="/maze_info",
        * operationId="mazeInfo",
        * tags={"Maze Info"},
        * summary="Get Maze Info",
        * description="Maze Info is retrieved here",
        *      
       *      @OA\Response(
        *          response=200,
        *          description="Maze Info fetched Successfully",
        *          @OA\JsonContent(            
        *               example={
        *            "code": 200,
        *            "data": {
        *                "message": "Maze Info fetched successfully",
        *                "maze_info": {
        *                    "id": 1,
        *                    "logo": "images/64c127d67ae1c.png",
        *                    "bg-color": "#3891CB",
        *                    "font": "Arial",
        *                    "administration_id": 1,
        *                    "created_at": "2023-07-25T14:33:21.000000Z",
        *                    "updated_at": "2023-07-26T14:04:06.000000Z"
        *                }
        *            }
        *        }),
        *       ),
 
        *      @OA\Response(
        *          response=400,
        *          description="Bad Request",
        *          @OA\JsonContent(            
        *               example={
        *                   "error": "Invalid token",
        *                   "message": "The provided token is not in the expected format or is malformed."
        *               }
        *       ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Resource not found",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": "404",
        *                   "message": "Maze Info Not Found",
        *               }
        *       ),
        *       ),

        * )
        */
    public function maze_info(){
        try{
            $maze_info = Auth()->user()->maze_info;
        }catch(Exception $e){
            return response()->json([
                'code' =>404,
                'message' =>'maze info cannot be fetched'
            ],404);
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
