<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


 
//use App\Http\Requests\LoginRequest;
//use App\Http\Requests\RegisterRequest;

use App\User;
use Validator;
use Illuminate\Support\Facades\Auth;


 
class AuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    private function guard()
    {
        return Auth::guard();
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    private function guard()
    {
        return Auth::guard();
    }


    /*-------------------------- */

    public function postRegister(Request $request)
    {
        $user = new User();
 
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->save();
 
        return response()->json([
           'success' => true
        ]);
    }

    
 
    public function postLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = null;
 
        try {
           if (!$token = \JWTAuth::attempt($credentials)) {
               return response()->json([
                 'success' => false,
                  'errors' => [
                    'message' => [
                       'Either Email or Password Invalid'
                    ]
                  ],
               ]);
           }
        } catch (\JWTAuthException $e) {
            return response()->json([
               'success' => false,
               'errors' => [
                  'message' => [
                     'Either Email or Password Invalid'
                   ]
                ],
            ]);
        }
 
        return $this->respondWithToken($token);
    }
 
    protected function respondWithToken($token)
    {
        $user = Auth::user();
 
        return response()->json([
          'success' => true,
          'access_token' => $token,
          'user' => $user,
          'token_type' => 'bearer',
        ]);
    }
    public function logout()
    {
        \JWTAuth::invalidate(\JWTAuth::getToken());
           return response()->json([
             'success' => true
           ]);
    }
}
