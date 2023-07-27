<?php
namespace App\Http\Controllers;

use App\Models\EmployeeIncrementHistoryModel;
use App\Models\EmployeesModel;
use Exception;
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

    /**
        * @OA\Post(
        * path="/administration/login",
        * operationId="authLogin",
        * tags={"Administration"},
        * summary="Administartion Login",
        * description="Login Administartion Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
        *          @OA\JsonContent(            
        *               example={
        *                 
        *           "code": 200,
        *            "data": {
        *                "message": "Login successful",
        *                "user": {
        *                    "id": 1,
        *                    "name": "Maze Administration",
        *                    "email": "maze_administration@gmail.com",
        *                    "created_at": "2023-07-25T14:33:21.000000Z",
        *                    "updated_at": "2023-07-25T14:33:21.000000Z"
        *                },
        *                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzUxMiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW5pc3RyYXRpb24vbG9naW4iLCJpYXQiOjE2OTAzNTk4NzYsImV4cCI6MTY5MDM3NDI3NiwibmJmIjoxNjkwMzU5ODc2LCJqdGkiOiJyaE5XYlBRdWlTUTR4azhtIiwic3ViIjoiMSIsInBydiI6ImI5OWExMWZkYzVmNWRiMDE5NjM2ZmVkODQ2NWUyZDlkYzQ4Yzg1YzYifQ.rAYX1lKYZ-5L0VCwm7Ked6zKnc9zuMygSVVwNJET-YtikLV9RJ_J5G5iZOpkDSdDLFIUIWlybJJKy1cUvv2ofyzUd9gS0JavOJJ3bpUi928NyYqxQtrQvaWmlEVt9NdcUCayGQmDCkuZvir_sYtqhv0or3cHtF02IAKaLTZ-d0SNVgDIrq4rSTF0SCCaWquKhr6NIPLMRUVvGxWKntlUapWv1WtQAS2rxqlJi6RCmI8ULB8tpHgN-ZNY2L5u5TD42_hVzzBVe5j0SwwVA9NrKVU1Gp0xyDIBLQgLISx5dgG-DWgugdeCdJ4rPxkma4nYWzZTs2rkjVG7KlfYNRdE5PRvYgEI2d7kWI8YkXqsPjUgQXvUy47bZT9wj9cij4bX81endH7ijfd6lYzV-yqgTYPgqwnAr0hl_euSjRDhDxz3KFpnsMaov-l4Eqo7TrBPcT4m5ScEB41ZKEb6moAdmwkCleh5OOmuEcRyEYqY_rtWn80HEtTjSBwo2CR0S4zYYN89R66r8p0-fpQlSJmeiYNl2yS0hvPwhE3Us9yYZbGrZc2fKWx7V65E6ppbZ3gp2RMQKB0GPGI6ApyZvjIRBC7wpNASBG_RLBZK4w8ER24oZu8YVC4e0wtg-rVkWRD5lYopD_Gf97LgwVSghcuMUbjIBgEWJSSGyUmheM1k4Fg",
        *                "timestamp": "2023-07-26T08:24:36.784868Z"
        *            }
        * 
        *       })
        *       ),
        *      @OA\Response(
        *          response=401,
        *          description="Unauthorized",
        *          @OA\JsonContent(            
        *               example={
        *                 
        *           "code": 401,
        *                "message": "Invalid credentials",
        *                "timestamp": "2023-07-26T08:24:36.784868Z"
        * 
        *       })
        *       ),


        * )
        */

        
        public function login(Request $request) {

            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);


            $credentials = ['email' => $request->email, 'password' => $request->password];
            try{
                $token = Auth::guard('administration')->attempt($credentials, true);
            }catch(Exception $e){
                return response()->json([
                    "code" => 409,
                    "message" => "Attempt failed, tokeen cannot be created "
                ]);
            }
            $timestamp = Carbon::now();
       

            $administration = Auth::guard('administration')->user();

            if($administration) {
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
                    "code"=> 401,
                    "message"=> "Invalid credentials",
                    'timestamp'=> $timestamp
                ],401);
            }

        }
  /**
        * @OA\Get(
        * path="/administration/logout",
        * operationId="Logout",
        * tags={"Administration"},

        * summary="Administartion Logout",
        * description="Logout Administartion Here",

        *      
        *      @OA\Response(
        *          response=200,
        *          description="Logout Successfully",
        *          @OA\JsonContent(            
        *               example={ 
        *                   "code": 200,
        *                   "message": "Logout successful",      
        *                   "timestamp": "2023-07-26T08:24:36.784868Z"
        *            }
        * 
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

        * 
        *       ),
        *       ),
        *     security={{"bearer_token":{}}}
        * ),
        */

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

           /**
        * @OA\Put(
        * path="/administration/update",
        * operationId="Update",
        * tags={"Administration"},
        * summary="Administartion update",
        * description="update Administartion Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name", "email"},
        *               @OA\Property(property="name", type="string"),
        *               @OA\Property(property="email", type="email"),
        *            ),
        *        ),
        *    ),
        *      
        *      @OA\Response(
        *          response=200,
        *          description="updated Successfully",
        *          @OA\JsonContent(            
        *               example={
        *            "code": 200,
        *            "message": "administration updated successfully",
        *            "data": {
        *                "user": {
        *                    "id": 1,
        *                    "name": "John",
        *                    "email": "johnn@example.com",
        *                    "created_at": "2023-07-25T14:33:21.000000Z",
        *                    "updated_at": "2023-07-26T10:58:04.000000Z"
        *                },
        *                "timestamp": "2023-07-26T10:58:04.524072Z"
        *            }
        *        })
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Resource not found",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": 404,
        *                   "message": "Administration not found",
        *               }

        * 
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

        * 
        *       ),
        *       ),
        *       @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": "422",
        *                   "message": "The administration could not be updated. Please check the provided data and try again.",
        *               }
        *       ),
        *       ),
        *     security={{"bearer_token":{}}}


        * )
        */
        public function update(Request $request) {


            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255|email|unique:administrations,email,'.Auth()->user()->id,
            ]);
            $timestamp = Carbon::now();
            if (!is_array($validated)) {
                response()->json([
                    'code'=>409,
                    'message' => 'Validation failed',
                    'timestamp' =>$timestamp
                ],409);
            }
            try{
                $administration = Administration::find(Auth()->guard('administration')->user()->id);
            }catch(Exception $e){
                return response()->json([
                    "code" => 404,
                    "message" => "Administration not found",
                ]);
            }
            
            
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
          
           /**
        * @OA\Put(
        * path="/administration/resetPassword",
        * operationId="resetPassword",
        * tags={"Administration"},
        * summary="Administartion Update Password",
        * description="Update Administartion Password Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"current_password", "password","password_confirmation"},
        *               @OA\Property(property="current_password", type="current_password"),
        *               @OA\Property(property="password", type="password"),
        *               @OA\Property(property="password_confirmation", type="password_confirmation"),
        *            ),
        *        ),
        *    ),
        *      
        *      @OA\Response(
        *          response=200,
        *          description="Password Updated Successfully",
        *          @OA\JsonContent(            
        *               example={
        *            "code": 200,
        *            "message": "Administration Password Updated successfully",
        *            "data": {
        *                "user": {
        *                    "id": 1,
        *                    "name": "John",
        *                    "email": "johnn@example.com",
        *                    "created_at": "2023-07-25T14:33:21.000000Z",
        *                    "updated_at": "2023-07-26T10:58:04.000000Z"
        *                },
        *                "timestamp": "2023-07-26T10:58:04.524072Z"
        *            }
        *        })
        *       ),
                *      @OA\Response(
        *          response=401,
        *          description="Unauthorized",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": "401",
        *                   "message": "Old Password is incorrect",
        *               }
        *       ),
        *        ),
        *      @OA\Response(
        *          response=404,
        *          description="Resource not found",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": "404",
        *                   "message": "Administration not found",
        *               }
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
        *       @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent(            
        *               example={
        *                   "code": "422",
        *                   "message": "The administration password could not be updated. Please check the provided data and try again.",
        *               }
        *       ),
        *       ),
        *     security={{"bearer_token":{}}}


        * )
        */
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
                ],401);

            }
            try {
                $administration = Administration::find(Auth()->user()->id);
            }catch (Exception $e) {
                return response()->json([
                    'code'=>404,
                    'message' => 'Adnministration not found',
                    'timestamp' => $timestamp
                ],404);
            }
            
            $token = Auth::guard('administration')->login($administration);

            if (!$token) {
                return response()->json([
                    'code'=>422,
                    'message' => "The administration password could not be updated. Please check the provided data and try again.",
                    'timestamp' => $timestamp
                ],422);
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

