<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.users.index', ['users' => User::when($request->filled('role'), fn ($q) => $q->where('role', $request->string('role')))->latest()->paginate(20)->withQueryString()]);
    }

    public function create(): View { return view('admin.users.create'); }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name'=>['required','string','max:120'],'email'=>['required','email','unique:users,email'],'phone'=>['nullable','string','max:30'],'store_name'=>['nullable','string','max:190'],'address'=>['nullable','string','max:1000'],'role'=>['required',Rule::enum(UserRole::class)],'password'=>['required','confirmed',Password::min(8)->letters()->numbers()],'is_active'=>['nullable','boolean']]);
        $data['is_active'] = $request->boolean('is_active');
        $user = User::create($data);
        ActivityLogger::log($request, 'user.created', "Membuat akun {$user->email}", $user);
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    public function edit(User $user): View { return view('admin.users.edit', compact('user')); }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id && !$request->boolean('is_active'), 422, 'Anda tidak dapat menonaktifkan akun sendiri.');
        $data = $request->validate(['name'=>['required','string','max:120'],'email'=>['required','email',Rule::unique('users')->ignore($user->id)],'phone'=>['nullable','string','max:30'],'store_name'=>['nullable','string','max:190'],'address'=>['nullable','string','max:1000'],'role'=>['required',Rule::enum(UserRole::class)],'password'=>['nullable','confirmed','min:8'],'is_active'=>['nullable','boolean']]);
        if ($user->role === UserRole::SUPERADMIN && $data['role'] !== UserRole::SUPERADMIN->value && User::where('role', UserRole::SUPERADMIN->value)->where('is_active', true)->count() <= 1) {
            return back()->with('error', 'Minimal satu Superadmin aktif harus tetap tersedia.')->withInput();
        }
        if (empty($data['password'])) unset($data['password']);
        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);
        ActivityLogger::log($request, 'user.updated', "Memperbarui akun {$user->email}", $user);
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }
}
