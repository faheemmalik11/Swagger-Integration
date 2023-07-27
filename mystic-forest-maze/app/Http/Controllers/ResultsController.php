<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Result;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ResultsController extends Controller
{
     /**
        * @OA\Post(
        * path="/result",
        * operationId="result",
        * tags={"Result"},
        * summary="Result Create",
        * description="Result Creation Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"completion_time","team","codes"},
        *               @OA\Property(property="completion_time", type="string"),
        *               @OA\Property(property="team", type="string"),
        *               @OA\Property(property="codes", type="array",@OA\Items(type="string")),
        *            ),
        *        ),
        *    ),
        *      
        *      @OA\Response(
        *          response=200,
        *          description="Result Created Successfully",
        *          @OA\JsonContent(            
        *               example={
        *            "code": 200,
        *            "data": {
        *                "message": "results created successfully",
        *                "result": {
        *                    "completion_time": "03:24",
        *                    "team": "Avengers",
        *                    "updated_at": "2023-07-26T14:52:55.000000Z",
        *                    "created_at": "2023-07-26T14:52:55.000000Z",
        *                    "id": 14
        *                }
        *            }
        *        }
        *       ),
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
        *          response=401,
        *          description="Codes Verification failed",
        *          @OA\JsonContent(            
        *               example={
        *            "code": 401,
        *            "data": {
        *                "message": "Codes could not be verified",
        *                "codes": {
        *                "adsda": false,
        *                "tHG2Mi": true,
        *                "X1ePIz": true,
        *                "A7GH198": true
        *            }
        *            }
        *        }
        *       ),
        *       ),
        *       @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": "422",
        *                   "message": "The result could not be created. Please check the provided data and try again.",
        *               }
        *       ),
        *       ),



        * )
        */
   
    public function create(){
        $validated = request()->validate([
            'completion_time'=>'required|date_format:i:s',
            'team'=>'required|string|min:2|max:255',
            'codes'=>'required|array|min:4',
            'codes.*'=>"required|string|distinct|min:3",
        ]);


        
        $codes = [];
        $failed_codes= [];
        foreach(request('codes') as $code){
            $value = Code::where('value','=',$code)->get();

            if(count($value) == null){
                array_push($failed_codes,$code);
                $codes[$code] = False;
           }else{

            $codes[$code] = True;
           }
        }

        if(count($failed_codes)!= null){
            return response()->json([
                'code'=>401,
                'data' => [
                    'message' => 'codes failed to verify',
                    'codes'=>$codes
                ]
            ],401);
        }
 
        try{
            $result = Result::create([
                'completion_time'=>Carbon::createFromFormat('i:s', request('completion_time'))->format('i:s'),
                'team'=>request('team'),
            ]);
        }catch(Exception $e){
            return response()->json([
                'code'=>422,
                "message"=> "The result could not be created. Please check the provided data and try again.",
            ],422);
        }
        return response()->json([
            'code'=>200,
            'data'=>[
                'message'=>'results created successfully',
                'result'=>$result
            ]
        ]);
    }

    /**
        * @OA\Get(
        * path="/result/{time}",
        * operationId="ResultGet",
        * tags={"Result"},
        * summary="Get Results",
        * description="Result are fetched by giving day week or year as parameter",
        *     @OA\Parameter(
        *         name="time",
        *         in="path",
        *         description="Day, week, or year as parameter",
        *         required=true,
        *         @OA\Schema(type="string")
        *     ),
        *      @OA\Response(
        *          response=200,
        *          description="Code Retrieved Successfully",
        *          @OA\JsonContent(            
        *               example={
        *          
        *                    "code": 200,
        *                    "data": {
        *                        "message": "Results Fetched successfully",
        *                        "results": {
        *                            {
        *                                "id": 13,
        *                                "completion_time": "03:24",
        *                                "team": "Avengers",
        *                                "created_at": "2023-07-26T14:34:53.000000Z",
        *                                "updated_at": "2023-07-26T14:34:53.000000Z"
        *                            },
        *                            {
        *                                "team": "Ryzeos",
    *                                   "completion_time": "01:32",
        *                                "created_at": "2023-07-26T14:52:55.000000Z",
        *                                "updated_at": "2023-07-26T14:52:55.000000Z"
        *                            }
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
        *                   "message": "No Records Found",
        *               }
        *       ),
        *       ),

        * )
        */
    public function get_results(){
        $day = Carbon::now();
        $catchResponse = response()->json([
            'code'=>404,
            'message'=>'No records found'
        ],404);
        if(ucfirst(request('time')) == 'Day'){
            try{
                $results = Result::whereDay('created_at', $day->day)->orderBy('completion_time','asc')->get();       
        }catch(Exception $e){
            return $catchResponse;
        }

        return response()->json([
            'code'=>200,
            'data'=>[
                'message'=>'Results Fetched successfully',
                'results'=> $results
            ],
        ]);
    }
    if(ucfirst(request('time')) == 'Week' ){
        try{
            
            $start_week = $day->startOfWeek()->format('Y-m-d');
            $end_week = $day->endOfWeek()->format('Y-m-d');
            $results = Result::whereBetween('created_at', [$start_week,$end_week])->orderBy('completion_time','asc')->get();      
        }catch(Exception $e){
            return $catchResponse;
        }

        return response()->json([
            'code'=>200,
            'data'=>[
                'message'=>'Results Fetched successfully',
                'results'=> $results
            ],
        ]);
    }
    if(ucfirst(request('time')) == 'Year' ){
        try{
            
            $year = Carbon::parse($day)->format('Y');
            $results = Result::byYear($year)->orderBy('completion_time','asc')->get(); 
        }catch(Exception $e){
            return $catchResponse;
        }

        return response()->json([
            'code'=>200,
            'data'=>[
                'message'=>'Results Fetched successfully',
                'results'=> $results,
            ],
        ]);
    }

}

/**
        * @OA\Get(
        * path="/result",
        * operationId="AllResultsGet",
        * tags={"Result"},
        * summary="Get All Results",
        * description="All results are fetched from the database",
        *      @OA\Response(
        *          response=200,
        *          description="Code Retrieved Successfully",
        *          @OA\JsonContent(            
        *               example={
        *          
        *                    "code": 200,
        *                    "data": {
        *                        "message": "Results Fetched successfully",
        *                        "results": {
        *                            {
        *                                "id": 13,
        *                                "completion_time": "03:24",
        *                                "team": "Avengers",
        *                                "created_at": "2023-07-26T14:34:53.000000Z",
        *                                "updated_at": "2023-07-26T14:34:53.000000Z"
        *                            },
        *                            {
        *                                "team": "Ryzeos",
    *                                   "completion_time": "01:32",
        *                                "created_at": "2023-07-26T14:52:55.000000Z",
        *                                "updated_at": "2023-07-26T14:52:55.000000Z"
        *                            }
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
        *                   "message": "No Records Found",
        *               }
        *       ),
        *       ),

        * )
        */
    public function get_all_results(){
        try{
            $results = Result::orderBy('id','DESC')->get();
        }catch(Exception $e){
            return response()->json([
                'code'=>404,
                'message'=>'Records cannot be fetched'
            ],404);
        }
        if(count($results) == 0){
            return response()->json([
                'code'=>200,
                'message'=>'No records found'
            ]);
        }
        return response()->json([
            'code'=>200,
            'data'=>[
                'message'=>'Successly fetched all records',
                'results'=>$results
            ]
        ]);
    }
}

                

