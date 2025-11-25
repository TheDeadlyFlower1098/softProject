<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginAuthController extends Controller
{
    public function attempt(Request $request): RedirectResponse
    {
        // Validate inputs (still coming from your form fields)
        $request->validate([
            'Email_Login'    => 'required|email',
            'Password_Login' => 'required|min:6',
        ]);

        // Build credentials array for Auth::attempt
        $credentials = [
            'email'    => $request->input('Email_Login'),
            'password' => $request->input('Password_Login'),
        ];

        // Tries to log the user in against the users table
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        // If login failed
        return back()->withErrors([
            'login' => 'Invalid email or password.',
        ])->onlyInput('Email_Login');
    }
}

