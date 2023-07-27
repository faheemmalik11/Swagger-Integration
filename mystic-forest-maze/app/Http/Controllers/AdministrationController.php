<?php
namespace App\Http\Controllers;

use App\Models\EmployeeIncrementHistoryModel;
use App\Models\EmployeesModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Administration;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class AdministrationController extends Controller
{




    // START: Administrations Apis
        public function login(Request $request) {

            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);


            $credentials = ['email' => $request->email, 'password' => $request->password];

            $token = Auth::guard('administration')->attempt($credentials, true);
            $timestamp = Carbon::now();
            if (!$token) {
            
                return response()->json([
                    "code"=> 401,
                    "message"=> "Invalid credentials",
                    'timestamp'=> $timestamp
                ]);
            }

            $administration = Auth::guard('administration')->user();

            if($token) {
                return response()->json([
                    "code"=> 200,
                    'data'=>[
                        "message"=> "Login successful",
                        'user' => $administration,
                        "token"=> $token,
                        'timestamp'=> $timestamp
                    ]
                ]);
            } else {
                return response()->json([
                    'code' => 409,
                    'message' => 'Inactive Administrations',
                    'data' => [
                        'user' => $administration,
                        'token' => $token,
                        'timestamp'=> $timestamp
                    ]
                ]);
            }

        }


        public function logout(): JsonResponse
        {
            $timestamp = Carbon::now();
            $logout = Auth::guard('administration')->logout();
            return response()->json([
                'code' => 200,
                'message' => 'Successfully logged out',
                'timestamp' => $timestamp
            ]);
        }

        public function update(Request $request) {


            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|unique:administrations,email,'.Auth()->user()->id,
            ]);
            $timestamp = Carbon::now();
            if (!is_array($validated)) {
                response()->json([
                    'code'=>409,
                    'message' => 'Validation failed',
                    'timestamp' =>$timestamp
                ]);
            }

            $administration = Administration::find(Auth()->guard('administration')->user()->id);
            
            $administration->name = ucfirst($request->name);
            $administration->email = $request->email;

            $administration->save();


            return response()->json([
                'code' => 200,
                'message' => 'administration updated successfully',
                'data'=>[
                    'user' => $administration,
                    'timestamp' => $timestamp
                ]
            ]);
        }
          
        public function resetPassword(Request $request)
        {
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string|min:6',
            ]);

            $timestamp = Carbon::now();
            if (Hash::check($request->current_password, Auth()->user()->password)) {

                Auth()->user()->fill([
                    'password' => Hash::make($request->password),
                ])->save();

            } else {

                return response()->json([
                    'code'=>401,
                    'message' => 'Old Password is Incorrect!',
                    'timestamp'=>$timestamp
                ]);

            }

            $administration = Administration::find(Auth()->user()->id);
            $token = Auth::guard('administration')->login($administration);

            if (!$token) {
                return response()->json([
                    'code'=>409,
                    'message' => 'User not updated',
                    'timestamp' => $timestamp
                ]);
            }

            return response()->json([
                'code' => 200,
                'message' => 'administrations password updated successfully',
                'data' =>[
                    'user' => $administration,
                    'token' => $token,
                    'timestamp' => $timestamp
                ]
            ]);
        }

       

    // END: Administrations Apis


  

}

