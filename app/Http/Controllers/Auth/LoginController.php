<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    public function index(): Response
    {
        return response()
            ->view('auth.login');
    }

    public function store(): RedirectResponse
    {
        request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!auth()->attempt(request()->only(['email', 'password']))) {
            return back()->withErrors([
                'email' => 'The provided credentials are incorrect.'
            ])->onlyInput('email');
        }

        request()->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::DASHBOARD);
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
