<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => Setting::pluck('value', 'key')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name'=>['required','string','max:190'],'site_tagline'=>['nullable','string','max:255'],'store_email'=>['nullable','email'],'store_phone'=>['nullable','string','max:30'],'store_address'=>['nullable','string','max:1000'],'whatsapp'=>['nullable','string','max:30'],'instagram'=>['nullable','string','max:190'],'payment_instruction'=>['nullable','string','max:2000'],'qris_image'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);
        foreach ($data as $key => $value) {
            if ($key === 'qris_image') continue;
            Setting::updateOrCreate(['key' => $key], ['value' => $value, 'group' => str_starts_with($key, 'payment') ? 'payment' : 'general']);
        }
        if ($request->hasFile('qris_image')) {
            $old = Setting::valueOf('qris_path'); if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('qris_image')->store('settings', 'public');
            Setting::updateOrCreate(['key'=>'qris_path'], ['value'=>$path,'type'=>'image','group'=>'payment']);
        }
        ActivityLogger::log($request, 'settings.updated', 'Memperbarui pengaturan website');
        return back()->with('success', 'Pengaturan website diperbarui.');
    }
}
