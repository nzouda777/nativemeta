<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function show()
    {
        return Inertia::render('Auth/Login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $user->update(['last_login_at' => now()]);

            activity()
                ->causedBy($user)
                ->withProperties(['ip' => $request->ip()])
                ->log('Connexion réussie');

            if ($user->isAdmin()) {
                return redirect()->intended('/admin');
            }

            return redirect()->intended('/dashboard');
        }

        activity()
            ->withProperties([
                'ip' => $request->ip(),
                'email' => $request->input('email'),
            ])
            ->log('Tentative de connexion échouée');

        return back()->withErrors([
            'email' => 'Les identifiants ne correspondent pas.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
