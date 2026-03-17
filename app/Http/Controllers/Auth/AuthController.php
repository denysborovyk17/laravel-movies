<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\{RegisterRequest, LoginRequest};
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): RedirectResponse
    {
        $userDTO = $request->toDTO();

        $user = User::create([
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'password' => Hash::make($userDTO->password),
            'role' => UserRole::USER->value,
        ]);

        Auth::login($user);

        return redirect()->intended(route('movies.index'))
            ->with('success', 'Реєстрація успішна! Ласкаво просимо!');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember_me'))) {
            $request->session()->regenerate();

            return redirect()->intended('movies');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return redirect()->route('index');
    }
}
