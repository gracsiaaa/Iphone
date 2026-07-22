@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <section class="page-section">
        <div class="site-shell">
            <div class="grid gap-10 lg:grid-cols-2 lg:gap-14">
                <div>
                    <p class="eyebrow">Hubungi toko</p>
                    <h1 class="page-title mt-3">Butuh informasi stok atau transaksi?</h1>
                    <p class="body-copy mt-5 max-w-lg">
                        Kirimkan pertanyaan melalui formulir. Pesan akan masuk langsung ke
                        dashboard Superadmin.
                    </p>

                    <div class="mt-9 grid gap-4">
                        <div class="surface panel-padding">
                            <p class="meta-text">WhatsApp</p>
                            <p class="mt-1 font-bold">
                                {{ $siteSettings->get(
                                    'whatsapp',
                                    $siteSettings->get('store_phone', '08xx-xxxx-xxxx')
                                ) }}
                            </p>
                        </div>

                        <div class="surface panel-padding">
                            <p class="meta-text">Email</p>
                            <p class="mt-1 font-bold">
                                {{ $siteSettings->get('store_email', 'hello@example.com') }}
                            </p>
                        </div>

                        <div class="surface panel-padding">
                            <p class="meta-text">Alamat</p>
                            <p class="mt-1 font-bold">
                                {{ $siteSettings->get('store_address', 'Alamat toko Anda') }}
                            </p>
                        </div>
                    </div>
                </div>

                <form
                    action="{{ route('contact.store') }}"
                    method="POST"
                    class="surface panel-padding"
                >
                    @csrf

                    <div class="form-grid">
                        <div class="field field-full">
                            <label class="label" for="contact-name">Nama</label>
                            <input
                                id="contact-name"
                                class="input"
                                name="name"
                                value="{{ old('name') }}"
                                required
                            >
                        </div>

                        <div class="field">
                            <label class="label" for="contact-email">Email</label>
                            <input
                                id="contact-email"
                                class="input"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                            >
                        </div>

                        <div class="field">
                            <label class="label" for="contact-phone">Nomor WhatsApp</label>
                            <input
                                id="contact-phone"
                                class="input"
                                name="phone"
                                value="{{ old('phone') }}"
                            >
                        </div>

                        <div class="field field-full">
                            <label class="label" for="contact-subject">Subjek</label>
                            <input
                                id="contact-subject"
                                class="input"
                                name="subject"
                                value="{{ old('subject') }}"
                                required
                            >
                        </div>

                        <div class="field field-full">
                            <label class="label" for="contact-message">Pesan</label>
                            <textarea
                                id="contact-message"
                                class="input min-h-40"
                                name="message"
                                required
                            >{{ old('message') }}</textarea>
                        </div>

                        <div class="field-full">
                            <button class="btn-primary">Kirim Pesan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
