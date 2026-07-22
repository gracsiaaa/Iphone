<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (!$request->user()->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda sedang dinonaktifkan.']);
        }

        if ($request->user()->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'store_name' => ['nullable', 'string', 'max:190'],
            'address' => ['required', 'string', 'max:1000'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $data['role'] = UserRole::USER;
        $data['is_active'] = true;
        $user = User::create($data);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Akun reseller berhasil dibuat.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
