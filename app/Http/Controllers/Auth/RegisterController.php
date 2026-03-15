<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function __construct(
        private readonly InvitationService $invitationService
    ) {}

    public function show(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect('/')->with('error', 'Un token d\'invitation est requis pour s\'inscrire.');
        }

        $invitationToken = $this->invitationService->validateToken($token);

        if (!$invitationToken) {
            return redirect('/')->with('error', 'Ce lien d\'invitation est invalide ou a expiré.');
        }

        return Inertia::render('Auth/Register', [
            'token' => $token,
            'email' => $invitationToken->email,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $invitationToken = $this->invitationService->validateToken($request->input('token'));

        if (!$invitationToken) {
            return back()->withErrors(['token' => 'Ce lien d\'invitation est invalide ou a expiré.']);
        }

        $user = $this->invitationService->completeRegistration($invitationToken, [
            'name' => $request->input('name'),
            'password' => $request->input('password'),
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Bienvenue sur NativeMeta ! 🎉');
    }
}
