<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('phone', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'phone' => 'Неверные данные'
            ]);
        }

        if (auth()->user()->role !== 'admin') {
            Auth::logout();
            return back()->withErrors([
                'phone' => 'Нет доступа'
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.index');
    }
}
