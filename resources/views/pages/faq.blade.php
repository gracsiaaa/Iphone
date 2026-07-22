@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
    <section class="section-rule">
        <div class="site-shell py-12 text-center sm:py-14">
            <p class="eyebrow">Bantuan</p>
            <h1 class="page-title mt-3">Pertanyaan yang sering diajukan</h1>
            <p class="body-copy mx-auto mt-4 max-w-2xl">
                Informasi pembelian, pembayaran QRIS, stok, dan invoice.
            </p>
        </div>
    </section>

    <section class="page-section">
        <div class="site-shell max-w-4xl">
            @forelse($faqs as $category => $items)
                <section class="mb-10">
                    <h2 class="mb-4 text-xl font-bold">{{ $category }}</h2>

                    <div class="space-y-3">
                        @foreach($items as $faq)
                            <details class="surface group p-5 sm:p-6">
                                <summary
                                    class="flex cursor-pointer list-none items-center justify-between gap-4 font-bold"
                                >
                                    <span>{{ $faq->question }}</span>
                                    <span class="text-xl text-zinc-400 transition group-open:rotate-45">+</span>
                                </summary>

                                <p class="text-muted mt-4 border-t border-zinc-100 pt-4">
                                    {{ $faq->answer }}
                                </p>
                            </details>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="empty-state">
                    <h2 class="font-bold">Belum ada FAQ</h2>
                </div>
            @endforelse
        </div>
    </section>
@endsection
