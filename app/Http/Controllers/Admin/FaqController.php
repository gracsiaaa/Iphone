<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View { return view('admin.faqs.index', ['faqs' => Faq::orderBy('sort_order')->paginate(20)]); }
    public function create(): View { return view('admin.faqs.create'); }
    public function store(Request $request): RedirectResponse { Faq::create($this->data($request)); return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil dibuat.'); }
    public function edit(Faq $faq): View { return view('admin.faqs.edit', compact('faq')); }
    public function update(Request $request, Faq $faq): RedirectResponse { $faq->update($this->data($request)); return redirect()->route('admin.faqs.index')->with('success', 'FAQ diperbarui.'); }
    public function destroy(Faq $faq): RedirectResponse { $faq->delete(); return back()->with('success', 'FAQ dihapus.'); }
    private function data(Request $request): array { return $request->validate(['question' => ['required','string','max:255'], 'answer' => ['required','string'], 'category' => ['required','string','max:100'], 'sort_order' => ['required','integer','min:0'], 'is_active' => ['nullable','boolean']]) + ['is_active' => $request->boolean('is_active')]; }
}
