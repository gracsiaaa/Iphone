<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        $data = $request->validate([
            'login' => ['required', 'string', 'max:190'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($data['login']);
        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $login = Str::lower($login);

        $authenticated = Auth::attempt([
            $field => $login,
            'password' => $data['password'],
        ], $request->boolean('remember'));

        if (! $authenticated) {
            return back()
                ->withErrors([
                    'login' => 'Username/email atau password tidak sesuai.',
                ])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        if (! $request->user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'login' => 'Akun Anda sedang dinonaktifkan.',
            ]);
        }

        ActivityLogger::log(
            $request,
            'auth.login',
            'Login berhasil ke sistem'
        );

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
        $request->merge([
            'username' => Str::lower(trim((string) $request->input('username'))),
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'username' => [
                'required',
                'string',
                'min:4',
                'max:30',
                'regex:/^[a-z0-9._]+$/',
                'unique:users,username',
            ],
            'email' => [
                'required',
                'email',
                'max:190',
                'unique:users,email',
            ],
            'store_name' => ['nullable', 'string', 'max:190'],
            'address' => ['required', 'string', 'max:1000'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers(),
            ],
        ], [
            'username.regex' => 'Username hanya boleh berisi huruf kecil, angka, titik, dan garis bawah.',
            'username.unique' => 'Username tersebut sudah digunakan.',
            'email.unique' => 'Email tersebut sudah terdaftar.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.letters' => 'Password wajib mengandung setidaknya satu huruf.',
            'password.numbers' => 'Password wajib mengandung setidaknya satu angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $data['role'] = UserRole::USER;
        $data['is_active'] = true;

        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLogger::log(
            $request,
            'auth.register',
            'Membuat akun reseller dan masuk ke sistem'
        );

        return redirect()
            ->route('home')
            ->with('success', 'Akun reseller berhasil dibuat.');
    }

    public function logout(Request $request): RedirectResponse
    {
        ActivityLogger::log(
            $request,
            'auth.logout',
            'Logout dari sistem'
        );

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
