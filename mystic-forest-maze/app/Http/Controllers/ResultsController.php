<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Result;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ResultsController extends Controller
{
    public function create(){
        $validated = request()->validate([
            'completion_time'=>'required|date_format:H:i',
            'team'=>'required|string|min:2|max:255|unique:results,team',
            'codes'=>'required',
        ]);

        $todayDate = date('Y-m-d');
        
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
                'code'=>200,
                'data' => [
                    'message' => 'codes failed to verify',
                    'codes'=>$codes
                ]
            ]);
        }

        
        try{
            $result = Result::create([
                'completion_time'=>request('completion_time'),
                'date'=>$todayDate,
                'team'=>request('team'),
            ]);
        }catch(Exception $e){
            return response()->json([
                'code'=>409,
                'message'=>'Result not created'
            ]);
        }
        return response()->json([
            'code'=>200,
            'data'=>[
                'message'=>'results created successfully',
                'result'=>$result
            ]
        ]);
    }

    public function get_results(){
        $day = Carbon::now();
        $catchResponse = response()->json([
            'code'=>409,
            'message'=>'No records found'
        ]);
        if(ucfirst(request('timezone')) == 'Day'){
            try{
                $todayDate = date('Y-m-d');
                $results = Result::where('date', $todayDate)->orderBy('completion_time','asc')->get();       
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
    if(ucfirst(request('timezone')) == 'Week' ){
        try{
            
            $start_week = $day->startOfWeek()->format('Y-m-d');
            $end_week = $day->endOfWeek()->format('Y-m-d');
            $results = Result::whereBetween('date', [$start_week,$end_week])->orderBy('completion_time','asc')->get();      
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
    if(ucfirst(request('timezone')) == 'Year' ){
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
    public function get_all_results(){
        try{
            $results = Result::orderBy('id','DESC')->get();
        }catch(Exception $e){
            return response()->json([
                'code'=>409,
                'message'=>'Records cannot be fetched'
            ]);
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

                

