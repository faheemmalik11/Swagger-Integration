<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Exception;
use Illuminate\Http\Request;

class CodeController extends Controller
{
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
                'code'=>409,
                'message'=>'code cannot be created'
            ]);
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

    public function update(){
        $validated = request()->validate([
            'value'=>'required|string|max:255|unique:codes,value',
        ]);
        try {
            $code = Code::findorFail(request('id'));
            $code->value = request('value');
            $code->save();

        }catch(Exception $e){
            return response()->json([
                'code'=>409,
                'message'=>'no record found against given id'
            ]);
        }
        return response()->json([
            'code'=>200,
            'data'=>
            [   'message'=>'code updated successfully',
                'code'=>$code,
            ],
        ]);
    }

    public function code(){
        try {
            $code = Code::findorFail(request('id'));

        }catch(Exception $e){
            return response()->json([
                'code'=>409,
                'message'=>'no record found against given id'
            ]);
        }
        return response()->json([
            'code'=>200,
            'data'=>
            [   'message'=>'code retrieved successfully',
                'code'=>$code,
            ],
        ]);
    }

    public function code_list(){
        try {
            $codes = Code::all();

        }catch(Exception $e){
            return response()->json([
                'code'=>409,
                'message'=>'no record founds'
            ]);
        }

        if(count($codes) == 0){
            return response()->json([
                'code'=>200,
                'data'=>[
                    'message'=>'No records found',
                    'codes'=>$codes
                ]
            ]); 
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
