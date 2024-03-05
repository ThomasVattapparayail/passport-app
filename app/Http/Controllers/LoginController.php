<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $validator= Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id'=>'required'
        ]);
        
        if ($validator->fails())
         {
            return response()->json([$validator->errors()], 403);
         }
    

        $user = User::create([
            'role_id'=> $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
           
        ]);

        $resArr=[];
        $resArr['token']=$user->createToken('AuthToken')->accessToken;
        $resArr['name']=$user->name;
        return response()->json(['message' => 'Customer registered successfully', 'data' => $user], 201);
    }


    public function login(Request $request)
    {
        $value = \Config::get('app.passport_token');
        dd($value);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user=Auth::user();
            $resArr=[];
            $resArr['token']=$user->createToken('AuthToken')->accessToken;
            $resArr['name']=$user->name;
            return response()->json(['valid' => auth()->check()]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    public function logout(Request $request)
    {
       $request->user()->token()->revoke();
       $user=Auth::user();
       return response()->json(['message'=>"Logged out successfully",'data'=>$user]);
    }
}
