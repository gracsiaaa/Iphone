<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View { return view('admin.contacts.index', ['messages' => ContactMessage::latest()->paginate(20)]); }
    public function show(ContactMessage $contactMessage): View { $contactMessage->update(['is_read'=>true]); return view('admin.contacts.show', ['message'=>$contactMessage]); }
    public function destroy(ContactMessage $contactMessage): RedirectResponse { $contactMessage->delete(); return redirect()->route('admin.contacts.index')->with('success','Pesan dihapus.'); }
}
