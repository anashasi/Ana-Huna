<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\error;

class AuthController extends Controller
{
    public function register(Request $request)
    { 
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user); 

        return to_route('home');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 
            return to_route('home');
        }

        return back()->withErrors([
            'email' => 'بيانات التسجيل غير صحيحة',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }




    /*
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
        */
}
