<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request){
        try {
            $data = $request->validate([
            'f_name' => ['required', 'string', 'min:3'],
            'l_name' => ['required', 'string', 'min:1'],
            'email' => ['required', 'email', 'unique,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            ]);

            $user = User::create([
                'name' => $data['f_name'] . ' ' . $data['l_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            Auth::login($user);

            $request->session()->regenerate();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Account created successfully.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Failed to create account. Please try again.'
                ]);
        }

    }
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Invalid credentials'])
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
