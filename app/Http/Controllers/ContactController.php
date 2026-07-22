<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:190'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ContactMessage::create($data);
        return back()->with('success', 'Pesan Anda sudah diterima. Tim toko akan menghubungi Anda.');
    }
}
