<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function profile(Request $request): View
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:30'],
            'store_name' => ['nullable', 'string', 'max:190'],
            'address' => ['required', 'string', 'max:1000'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);
        if (!$data['password']) unset($data['password']);
        $user->update($data);
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function orders(Request $request): View
    {
        return view('account.orders.index', ['orders' => $request->user()->orders()->latest()->paginate(10)]);
    }

    public function showOrder(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id || $request->user()->isAdmin(), 403);
        $order->load(['items', 'payment']);
        return view('account.orders.show', compact('order'));
    }

    public function invoice(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id || $request->user()->isAdmin(), 403);
        $order->load(['items', 'payment', 'user']);
        return view('account.orders.invoice', compact('order'));
    }
}
