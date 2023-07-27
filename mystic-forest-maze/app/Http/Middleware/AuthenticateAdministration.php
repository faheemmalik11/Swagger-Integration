<?php

namespace App\Http\Middleware;





use App\Models\CompaniesModel;
use Exception;
use Illuminate\Http\Request;
use Closure;

use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;




class AuthenticateAdministration {

    /**
     * The JWT Authenticator.
     *
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
     
        try {


            $Token =JWTAuth::parseToken();
            if(!$Token){
                return response()->json(['message' => 'token not found', ], );
            }
            $administration = JWTAuth::parseToken()->authenticate();

            if (!$administration) {
                return response()->json(['message' => 'adminsitrator not found', 'administration' => $administration]);
            }

        } catch (Exception $e) {
            return response()->json(['message' => 'token cannot be parsed']);
        }

        if(auth()->guard('administration')->check()){
            return $next($request);
        } else {
            return response()->json([
                'status' => auth()->guard('administration')->check(),
                'message' => 'Please login with administration Account.',
            ]);
        }

    }

    }
