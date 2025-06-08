<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        
        $data=$request->validate([
            'name'=>'required|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed'
        ]);

        $user=User::create($data);

        $token=$user->createToken($user->name);

        return [
            'message'=>'تم التسجيل بنجاح',
            'user'=>$user,
            'token'=>$token->plainTextToken
        ];
    }

    public function login(Request $request){

        $data=$request->validate([
            'email'=>'required|email|exists:users',
            'password'=>'required'
        ]);

        $user=User::where('email', $request->email)->first();

        if(!$user|| !Hash::check($request->password,$user->password)){
            return[
                'error'=>'بيانات الدخول غير صحيحة'
            ];
        }

        $token=$user->createToken($user->name);

        return [
            'message'=>'تم تسجيل الدخول بنجاح',
            'user'=>$user,
            'token'=>$token->plainTextToken
        ];

    }

    public function logout(Request $request){

        $request->user()->tokens()->delete();

        return [
            'message'=>'تم تسجيل الخروج بنجاح'
        ];

    }

    public function updatePassword(Request $request){

        $data=$request->validate([
            'password'=>'required',
            'newPassword'=>'required'
        ]);

        $user=Auth::user();

        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'error'=>'كلمة السر غير صحيحة'
            ]);
        }

        $new_password_hashed=Hash::make($data['newPassword']);

        $user->update([
            'password'=>$new_password_hashed
        ]);
        
        return response()->json([
            'message'=>'تم تغيير كلمة السر بنجاح'
        ]);

    }
}
