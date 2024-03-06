<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshToken;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\File;

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
       
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user=Auth::user();
            $resArr=[];
            $dummy="";
            $resArr['token']=$user->createToken('AuthToken')->accessToken;
            $bearerToken="Bearer ".$resArr['token'];
            $bearerToken='"'.$bearerToken.'"';
            //dd($bearerToken);
            $this->updateEnvVariable($bearerToken);
            $resArr['name']=$user->name;
            return response()->json(['message' =>'Login Successfully']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();
       return response()->json(['message'=>"Logged out successfully"]);
    }

    public function updateEnvVariable($bearerToken)
    {
        
        $envFilePath = base_path('.env');

        $envContents = File::get($envFilePath);

        $pattern = '/^PASSPORT=.*/m';

        $replacement = 'PASSPORT='.$bearerToken;

        $newEnvContents = preg_replace($pattern, $replacement, $envContents);

        File::put($envFilePath, $newEnvContents);

    }
}
