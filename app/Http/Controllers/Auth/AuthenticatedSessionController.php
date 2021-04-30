<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use App\Models\User;
use Validator;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    //
    public function create(){
        return view('auth/login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        //$request->session()->regenerate();

        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        if($token){
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => User::where('email', $credentials['email'])->get()->first(),
            ], status:200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Wrong credentials',
                'errors' => $validator->errors(),
            ], status:401);
        }

    }

    public function refreshToken() {
        $token = JWTAuth::getToken();
        try{
            $token = JWTAuth::refresh($token);
            return response()->json([
                'success' => true,
                'token' => $token,
            ], status:200);
        } catch(TokenExpiredException $ex){
            return response()->json([
                'success' => false,
                'message' => 'Please log in again, your login is expired!',
            ], status:422);
        }catch(TokenBlacklistedException $ex){
            return response()->json([
                'success' => false,
                'message' => 'Please log in again!',
            ], status:422);
        }
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $token = JWTAuth::getToken();

        try{
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => true,
                'message' => 'Logout successful'],
                status:200);
        } catch (JWTException $ex){
            return response()->json([
                'success' => false,
                'message' => 'Logout successful'],
                status:422);
        }

        $request->session()->regenerateToken();

    }
}
