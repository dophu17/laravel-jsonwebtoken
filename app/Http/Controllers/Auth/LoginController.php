<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    function login(Request $request)
    {
        $inputs = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
        if ( Auth::attempt($inputs) ) {

            //json web token
            $jwt_token = null;
            try {
                if ( !$jwt_token = JWTAuth::attempt($inputs) ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Email or Password',
                    ], 401);
                }
            } catch ( JWTException $e ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, the user cannot be authenticated. <br>' . $e,
                ], 401);
            }
            return response()->json([
                'success' => true,
                'token' => $jwt_token,
            ]);
            //end json web token

            //return redirect()->route('home');
        }
        return redirect()->route('login');
    }

    function logout(Request $request) {
        echo 'token:';
        print_r($request->token);
        //json web token
        $this->validate($request, [
            'token' => 'required'
        ]);
        try {
            echo 'begin logout token';
            JWTAuth::invalidate($request->token);
            Auth::logout();
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception
            ], 500);
        }
        //end json web token
    }
}
