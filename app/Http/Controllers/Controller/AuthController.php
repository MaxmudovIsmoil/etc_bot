<?php

namespace App\Http\Controllers\Controller;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('layout.login');
    }


    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');


        if (!Auth::attempt($credentials))
            return redirect()->back()->with('error', 'Login or password for admin is incorrect.');

        $request->session()->regenerate();

        return redirect()->route('order.index');

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }

}
