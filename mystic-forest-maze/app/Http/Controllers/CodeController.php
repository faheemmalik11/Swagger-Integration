<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Exception;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    /**
        * @OA\Post(
        * path="/code/create",
        * operationId="create",
        * tags={"Code"},
        * summary="Code Create",
        * description="Code Creation Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"value"},
        *               @OA\Property(property="value", type="string"),
        *            ),
        *        ),
        *    ),
        *      
        *      @OA\Response(
        *          response=200,
        *          description="Code Created Successfully",
        *          @OA\JsonContent(            
        *               example={
        *                    "code": 200,
        *                    "data": {
        *                        "message": "code created successfully",
        *                        "code": {
        *                            "value": "A7GH198",
        *                            "updated_at": "2023-07-26T12:53:07.000000Z",
        *                            "created_at": "2023-07-26T12:53:07.000000Z",
        *                            "id": 1
        *                        }
        *                    }
        *                }),
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
        *       @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": "422",
        *                   "message": "The code could not be create. Please check the provided data and try again.",
        *               }
        *       ),
        *       ),
        *     security={{"bearer_token":{}}}


        * )
        */
    public function create(){
        $validated = request()->validate([
            'value'=>'required|string|max:255|unique:codes,value',
        ]);
        try {
            $code = Code::create([
                'value'=>request('value'),
            ]);
        }catch(Exception $e){
            return response()->json([
                'code'=>422,
                "message"=> "The code could not be created. Please check the provided data and try again."
            ],422);
        }
        return response()->json([
            'code'=>200,
            'data'=>
            [   
                'message'=>'code created successfully',
                'code'=>$code,
            ],
        ]);
    }

    /**
        * @OA\Put(
        * path="/code/update/{id}",
        * operationId="CodeUpdate",
        * tags={"Code"},
        * summary="Code Update",
        * description="Code Updation Here",
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the code",
        *         required=true,
        *         @OA\Schema(type="integer")
        *     ),
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"value"},
        *               @OA\Property(property="value", type="string"),
        *            ),
        *        ),
        *    ),
        *      
        *      @OA\Response(
        *          response=200,
        *          description="Code Updated Successfully",
        *          @OA\JsonContent(            
        *               example={
        *                    "code": 200,
        *                    "data": {
        *                        "message": "code update successfully",
        *                        "code": {
        *                            "value": "A422665",
        *                            "updated_at": "2023-07-26T12:53:07.000000Z",
        *                            "created_at": "2023-07-26T12:53:07.000000Z",
        *                            "id": 1
        *                        }
        *                    }
        *                }),
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
        *                   "message": "no record found against given id",
        *               }
        *       ),
        *       ),
        *       @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": "422",
        *                   "message": "The code could not be updated. Please check the provided data and try again.",
        *               }
        *       ),
        *       ),
        *     security={{"bearer_token":{}}}
        * )
        */
    public function update(){
        $validated = request()->validate([
            'value'=>'required|string|max:255|unique:codes,value',
        ]);
        try {
            $code = Code::findorFail(request('id'));
        }catch(Exception $e){
            return response()->json([
                'code'=>404,
                'message'=>'no record found against given id'
            ],404);
        }
        try{
            $code->value = request('value');
            $code->save();
        }catch(Exception $e){
            return response()->json([
                'code'=>422,
                "message"=> "The code could not be updated. Please check the provided data and try again.",
            ],422);
        }
        return response()->json([
            'code'=>200,
            'data'=>
            [   'message'=>'code updated successfully',
                'code'=>$code,
            ],
        ]);
    }
    /**
        * @OA\Get(
        * path="/code/{id}",
        * operationId="CodeGet",
        * tags={"Code"},
        * summary="Get Code By ID",
        * description="Code is retrieved by id",
        *     @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the code",
        *         required=true,
        *         @OA\Schema(type="integer")
        *     ),
        *      @OA\Response(
        *          response=200,
        *          description="Code Retrieved Successfully",
        *          @OA\JsonContent(            
        *               example={
        *                    "code": 200,
        *                    "data": {
        *                        "message": "code retrieved successfully",
        *                        "code": {
        *                            "id": 1,
        *                            "value": "1jEuOq",
        *                            "created_at": "2023-07-25T14:33:21.000000Z",
        *                            "updated_at": "2023-07-25T14:33:21.000000Z"
        *                        }
        *                    }
        *                }),
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
        *                   "message": "no record found against given id",
        *               }
        *       ),
        *       ),

        *     security={{"bearer_token":{}}}
        * )
        */

    public function code(){
        try {
            $code = Code::findorFail(request('id'));

        }catch(Exception $e){
            return response()->json([
                'code'=>404,
                'message'=>'no record found against given id'
            ],404);
        }
        return response()->json([
            'code'=>200,
            'data'=>
            [   'message'=>'code retrieved successfully',
                'code'=>$code,
            ],
        ]);
    }

   /**
        * @OA\Get(
        * path="/code/",
        * operationId="CodeGetAll",
        * tags={"Code"},
        * summary="Get All Codes",
        * description="Code are retrieved",
        *      
        *      @OA\Response(
        *          response=200,
        *          description="Code Retrieved Successfully",
        *          @OA\JsonContent(            
        *               example={
        *                    "code": 200,
        *                    "data": {
        *                        "message": "code retrieved successfully",
        *                        "code": {
        *                            "id": 1,
        *                            "value": "1jEuOq",
        *                            "created_at": "2023-07-25T14:33:21.000000Z",
        *                            "updated_at": "2023-07-25T14:33:21.000000Z"
        *                        },
        *                        {
        *                            "id": 2,
        *                            "value": "Kj2aX",
        *                            "created_at": "2023-07-25T14:33:21.000000Z",
        *                            "updated_at": "2023-07-25T14:33:21.000000Z"
        *                        },
        *                        {
        *                            "id": 3,
        *                            "value": "LaYA1",
        *                            "created_at": "2023-07-25T14:33:21.000000Z",
        *                            "updated_at": "2023-07-25T14:33:21.000000Z"
        *                        },
        *                    }
        *                }),
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
        *                   "message": "No Records Found",
        *               }
        *       ),
        *       ),

        *     security={{"bearer_token":{}}}
        * )
        */

    public function code_list(){
        try {
            $codes = Code::all();

        }catch(Exception $e){
            return response()->json([
                'code'=>404,
                'message'=>'Code cannot be fetched'
            ],404);
        }

        if(count($codes) == 0){
            return response()->json([
                'code'=>404,
                'data'=>[
                    'message'=>'No records found',
                    'codes'=>$codes
                ]
            ],404); 
        }
        return response()->json([
            'code'=>200,
            'data'=>
            [   
                'message'=>'Codes retrieved successfully',
                'codes'=>$codes,
            ],
        ]);
    }
   /**
        * @OA\Delete(
        * path="/code/delete/{id}",
        * operationId="CodeDelete",
        * tags={"Code"},
        * summary="Delete Code",
        * description="Code can be deleted here",
        *       @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="ID of the Code",
        *         required=true,
        *         @OA\Schema(type="integer")
        *     ),
        *      @OA\Response(
        *          response=200,
        *          description="Code Retrieved Successfully",
        *          @OA\JsonContent(            
        *               example={
        *                    "code": 200,
        *                    "data": {
        *                        "message": "code deleted successfully",
        *                        "code": {
        *                            "id": 1,
        *                            "value": "1jEuOq",
        *                            "created_at": "2023-07-25T14:33:21.000000Z",
        *                            "updated_at": "2023-07-25T14:33:21.000000Z"
        *                        },

        *                    }
        *                }),
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
        *                   "message": "No record found against given id",
        *               }
        *       ),
        *       ),

        *     security={{"bearer_token":{}}}
        * )
        */
    public function delete(){
        try{
            $code = Code::findorFail(request('id'));
            $code->delete();
        }catch(Exception $e){
            return response()->json([
                'code'=>409,
                'message'=>'No record found against given id'
            ]);
        }

        return response()->json([
            'code'=>200,
            'data'=>[
                'message'=>'Code deleted successfully',
                'code'=>$code
            ]
        ]);
    }


}
