<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return Inertia::render('Client/Profile', [
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'avatar' => $request->user()->avatar,
                'created_at' => $request->user()->created_at->format('d/m/Y'),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $request->user()->id],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'current_password' => ['required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $mediaService = app(MediaService::class);
            $validated['avatar'] = $mediaService->uploadAvatar($request->file('avatar'));
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'avatar' => $validated['avatar'] ?? $user->avatar,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
