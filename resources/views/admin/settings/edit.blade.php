@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('heading', 'Pengaturan Website')
@section('eyebrow', 'Superadmin')

@section('content')
    <form
        action="{{ route('admin.settings.update') }}"
        method="POST"
        enctype="multipart/form-data"
        class="editor-layout"
    >
        @csrf
        @method('PUT')

        <section class="surface form-grid p-7 sm:p-8">
            <div class="field-full">
                <h2 class="panel-title">Identitas website</h2>
                <p class="text-muted mt-1">
                    Ubah informasi toko tanpa menyentuh source code.
                </p>
            </div>

            <div class="field">
                <label class="label" for="site-name">Nama website</label>
                <input
                    id="site-name"
                    class="input"
                    name="site_name"
                    value="{{ old(
                        'site_name',
                        $settings->get('site_name', 'iPhone Reseller Store')
                    ) }}"
                    required
                >
            </div>

            <div class="field">
                <label class="label" for="site-tagline">Tagline</label>
                <input
                    id="site-tagline"
                    class="input"
                    name="site_tagline"
                    value="{{ old('site_tagline', $settings->get('site_tagline')) }}"
                >
            </div>

            <div class="field">
                <label class="label" for="store-email">Email toko</label>
                <input
                    id="store-email"
                    class="input"
                    type="email"
                    name="store_email"
                    value="{{ old('store_email', $settings->get('store_email')) }}"
                >
            </div>

            <div class="field">
                <label class="label" for="store-phone">Telepon toko</label>
                <input
                    id="store-phone"
                    class="input"
                    name="store_phone"
                    value="{{ old('store_phone', $settings->get('store_phone')) }}"
                >
            </div>

            <div class="field">
                <label class="label" for="whatsapp">WhatsApp</label>
                <input
                    id="whatsapp"
                    class="input"
                    name="whatsapp"
                    value="{{ old('whatsapp', $settings->get('whatsapp')) }}"
                >
            </div>

            <div class="field">
                <label class="label" for="instagram">Instagram</label>
                <input
                    id="instagram"
                    class="input"
                    name="instagram"
                    value="{{ old('instagram', $settings->get('instagram')) }}"
                >
            </div>

            <div class="field field-full">
                <label class="label" for="store-address">Alamat toko</label>
                <textarea
                    id="store-address"
                    class="input min-h-28"
                    name="store_address"
                >{{ old('store_address', $settings->get('store_address')) }}</textarea>
            </div>

            <div class="field field-full">
                <label class="label" for="payment-instruction">Instruksi pembayaran</label>
                <textarea
                    id="payment-instruction"
                    class="input min-h-32"
                    name="payment_instruction"
                >{{ old(
                    'payment_instruction',
                    $settings->get('payment_instruction')
                ) }}</textarea>
            </div>
        </section>

        <aside class="space-y-6">
            <section class="surface panel-padding">
                <h2 class="panel-title">QRIS toko</h2>
                <p class="field-note">
                    Unggah QRIS resmi toko. Gambar ini tampil pada halaman pembayaran reseller.
                </p>

                <img
                    src="{{ $settings->get('qris_path')
                        ? \Illuminate\Support\Facades\Storage::url(
                            $settings->get('qris_path')
                        )
                        : asset('images/qris-placeholder.svg') }}"
                    class="mx-auto mt-5 max-h-80 w-full rounded-xl border border-zinc-200 object-contain"
                    alt="QRIS toko"
                >

                <input
                    class="input mt-4"
                    type="file"
                    name="qris_image"
                    accept="image/*"
                >
            </section>

            <button class="btn-primary w-full">Simpan Pengaturan</button>
        </aside>
    </form>
@endsection
