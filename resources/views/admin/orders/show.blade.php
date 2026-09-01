@extends('layouts.admin')

@section('title', $order->invoice_number)
@section('heading', $order->invoice_number)
@section('eyebrow', 'Detail pesanan')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <x-order-badge :status="$order->status" />
        <a
            href="{{ route('orders.invoice', $order) }}"
            target="_blank"
            class="btn-secondary !py-2.5"
        >
            Cetak Invoice
        </a>
    </div>

    <div class="editor-layout">
        <div class="space-y-6">
            <section class="surface overflow-hidden">
                <div class="panel-header">
                    <h2 class="panel-title">Item pesanan</h2>
                </div>

                <div class="divide-y divide-zinc-100">
                    @foreach($order->items as $item)
                        <div class="flex flex-col justify-between gap-3 p-5 min-[480px]:flex-row sm:p-6">
                            <div>
                                <strong>{{ $item->product_name }}</strong>
                                <p class="text-muted mt-1">
                                    {{ $item->product_ram ? 'RAM '.$item->product_ram.' · ' : '' }}{{ $item->product_capacity }} ·
                                    {{ $item->product_color }} ·
                                    {{ $item->quantity }} unit
                                </p>
                                @if($item->imei)
                                    <p class="mt-1 font-mono text-xs text-zinc-500">
                                        IMEI: {{ $item->imei }}
                                    </p>
                                @endif
                            </div>
                            <strong class="text-right">
                                {{ 'Rp'.number_format((float) $item->subtotal, 0, ',', '.') }}
                            </strong>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-wrap justify-between gap-3 border-t border-zinc-200 bg-zinc-50 p-5 sm:p-6">
                    <strong>Total</strong>
                    <strong class="text-xl">{{ $order->formatted_total }}</strong>
                </div>
            </section>

            @if(in_array($order->status, [\App\Enums\OrderStatus::PAID, \App\Enums\OrderStatus::COMPLETED]))
                <section class="surface overflow-hidden">
                    <div class="panel-header">
                        <h2 class="panel-title">Nomor IMEI</h2>
                    </div>

                    <form
                        action="{{ route('admin.orders.update-imei', $order) }}"
                        method="POST"
                        class="divide-y divide-zinc-100"
                    >
                        @csrf
                        @method('PUT')

                        @foreach($order->items as $item)
                            <div class="p-5 sm:p-6">
                                <label class="text-sm font-semibold text-zinc-900">
                                    {{ $item->product_name }}
                                    <span class="font-normal text-zinc-500">
                                        ({{ $item->quantity }} unit)
                                    </span>
                                </label>
                                <input
                                    type="text"
                                    name="imei[{{ $item->id }}]"
                                    value="{{ old('imei.'.$item->id, $item->imei) }}"
                                    class="input mt-2 imei-input"
                                    data-quantity="{{ $item->quantity }}"
                                    placeholder="Scan atau ketik IMEI di sini..."
                                >
                                <div class="mt-2 flex items-center justify-between">
                                    @error('imei.'.$item->id)
                                        <span class="text-xs font-medium text-red-500">{{ $message }}</span>
                                    @else
                                        <span class="text-xs text-zinc-400">Pisahkan dengan koma jika ketik manual.</span>
                                    @enderror
                                    <span class="imei-counter text-xs font-semibold text-zinc-500">
                                        0 / {{ $item->quantity }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        <div class="bg-zinc-50 p-5 sm:p-6">
                            <button class="btn-primary w-full">Simpan IMEI</button>
                        </div>
                    </form>
                </section>
            @endif

            <section class="surface panel-padding">
                <h2 class="panel-title">Informasi reseller</h2>

                <div class="info-grid mt-5">
                    <div>
                        <span class="info-label">Nama</span>
                        <div class="info-value">{{ $order->customer_name }}</div>
                    </div>
                    <div>
                        <span class="info-label">Nama toko</span>
                        <div class="info-value">
                            {{ $order->customer_store_name ?: '-' }}
                        </div>
                    </div>
                    <div>
                        <span class="info-label">Email</span>
                        <div class="info-value">{{ $order->customer_email }}</div>
                    </div>
                    <div>
                        <span class="info-label">WhatsApp</span>
                        <div class="info-value">{{ $order->customer_phone }}</div>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="info-label">Alamat</span>
                        <div class="info-value">{{ $order->billing_address }}</div>
                    </div>
                </div>
            </section>
            <section class="surface overflow-hidden">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">Riwayat pesanan</h2>
                        <p class="mt-1 text-xs text-zinc-500">Audit trail untuk invoice ini</p>
                    </div>
                    <span class="rounded-full bg-zinc-100 px-2.5 py-1 text-xs font-bold text-zinc-600">
                        {{ $order->activityLogs->count() }} aktivitas
                    </span>
                </div>

                <div class="p-5 sm:p-6">
                    @forelse($order->activityLogs as $activity)
                        @php
                            [$dotClass, $badgeClass] = match ($activity->action) {
                                'order.created' => ['bg-violet-500', 'bg-violet-50 text-violet-700'],
                                'payment.submitted' => ['bg-amber-500', 'bg-amber-50 text-amber-700'],
                                'order.approved' => ['bg-emerald-500', 'bg-emerald-50 text-emerald-700'],
                                'order.rejected' => ['bg-red-500', 'bg-red-50 text-red-700'],
                                'order.completed' => ['bg-green-600', 'bg-green-50 text-green-700'],
                                default => ['bg-zinc-400', 'bg-zinc-100 text-zinc-700'],
                            };
                        @endphp

                        <div class="relative flex gap-4 pb-7 last:pb-0">
                            @if(! $loop->last)
                                <div class="absolute left-[7px] top-5 h-[calc(100%-0.25rem)] w-px bg-zinc-200"></div>
                            @endif
                            <div class="relative mt-1.5 h-3.5 w-3.5 shrink-0 rounded-full {{ $dotClass }} ring-4 ring-white"></div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $badgeClass }}">
                                        {{ $activity->action_label }}
                                    </span>
                                    <span class="text-xs text-zinc-400">
                                        {{ $activity->created_at->format('d M Y · H:i:s') }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm font-semibold text-zinc-900">{{ $activity->description }}</p>
                                <div class="mt-1 text-xs text-zinc-500">
                                    Dilakukan oleh
                                    <span class="font-bold text-zinc-700">{{ $activity->user?->name ?: 'System' }}</span>
                                    @if($activity->user)
                                        <span>({{ $activity->user->role->label() }})</span>
                                    @endif
                                    @if($activity->ip_address)
                                        <span class="text-zinc-300">·</span>
                                        <span class="font-mono text-[11px]">{{ $activity->ip_address }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 px-4 py-8 text-center text-sm text-zinc-500">
                            Belum ada audit trail untuk pesanan ini.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="surface panel-padding">
                <h2 class="panel-title">Pembayaran QRIS</h2>

                <dl class="mt-5 space-y-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-zinc-500">Status payment</dt>
                        <dd class="font-semibold">
                            {{ ucfirst(str_replace('_', ' ', $order->payment?->status ?? '-')) }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-zinc-500">Konfirmasi user</dt>
                        <dd>
                            {{ optional($order->payment?->confirmed_at)->format('d M H:i') ?: '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-zinc-500">Diverifikasi</dt>
                        <dd>
                            {{ optional($order->verified_at)->format('d M H:i') ?: '-' }}
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-zinc-500">Diverifikasi oleh</dt>
                        <dd class="text-right font-semibold">
                            @if($order->verifier)
                                {{ $order->verifier->name }}
                                <span class="block text-[11px] font-normal text-zinc-400">
                                    {{ $order->verifier->role->label() }}
                                </span>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>

                @if($order->payment?->proof_url)
                    <a href="{{ $order->payment->proof_url }}" target="_blank">
                        <img
                            src="{{ $order->payment->proof_url }}"
                            class="mt-5 max-h-72 w-full rounded-xl border border-zinc-200 object-contain"
                            alt="Bukti pembayaran"
                        >
                    </a>
                @else
                    <div class="surface-soft mt-5 p-4 text-center text-xs text-zinc-500">
                        User tidak mengunggah bukti pembayaran.
                    </div>
                @endif
            </section>

            @if($order->status === \App\Enums\OrderStatus::WAITING_VERIFICATION)
                <section class="surface space-y-5 panel-padding">
                    <h2 class="panel-title">Tindakan Admin</h2>

                    <form
                        action="{{ route('admin.orders.approve', $order) }}"
                        method="POST"
                        class="space-y-3"
                    >
                        @csrf
                        <textarea
                            class="input min-h-20"
                            name="admin_note"
                            placeholder="Catatan verifikasi (opsional)"
                        ></textarea>
                        <button class="btn-primary w-full">Setujui Pembayaran</button>
                    </form>

                    <form
                        action="{{ route('admin.orders.reject', $order) }}"
                        method="POST"
                        class="space-y-3 border-t border-zinc-200 pt-5"
                        onsubmit="return confirm('Tolak pesanan dan kembalikan stok?')"
                    >
                        @csrf
                        <textarea
                            class="input min-h-20"
                            name="admin_note"
                            required
                            placeholder="Alasan penolakan"
                        ></textarea>
                        <button class="btn-danger w-full">Tolak Pembayaran</button>
                    </form>
                </section>
            @elseif($order->status === \App\Enums\OrderStatus::PENDING_PAYMENT)
                <section class="surface panel-padding">
                    <p class="text-muted">User belum mengirim konfirmasi pembayaran.</p>
                    <form
                        action="{{ route('admin.orders.reject', $order) }}"
                        method="POST"
                        class="mt-4 space-y-3"
                        onsubmit="return confirm('Batalkan invoice dan kembalikan stok?')"
                    >
                        @csrf
                        <textarea
                            class="input min-h-20"
                            name="admin_note"
                            required
                            placeholder="Alasan pembatalan"
                        ></textarea>
                        <button class="btn-danger w-full">Batalkan Invoice</button>
                    </form>
                </section>
            @elseif($order->status === \App\Enums\OrderStatus::PAID)
                <form
                    action="{{ route('admin.orders.complete', $order) }}"
                    method="POST"
                    class="surface panel-padding"
                >
                    @csrf
                    <p class="text-muted">
                        Pembayaran sudah terverifikasi. Tandai selesai setelah transaksi
                        dituntaskan.
                    </p>
                    <button class="btn-primary mt-4 w-full">Tandai Selesai</button>
                </form>
            @endif
        </aside>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.imei-input');
    
    function updateCounter(input) {
        const val = input.value.trim();
        const parts = val ? val.split(',').map(s => s.trim()).filter(s => s.length > 0) : [];
        const quantity = parseInt(input.getAttribute('data-quantity'), 10) || 0;
        
        const counterEl = input.parentElement.querySelector('.imei-counter');
        if (counterEl) {
            counterEl.textContent = parts.length + ' / ' + quantity;
            if (parts.length === quantity) {
                counterEl.classList.remove('text-zinc-500', 'text-red-500');
                counterEl.classList.add('text-green-600');
            } else if (parts.length > quantity) {
                counterEl.classList.remove('text-zinc-500', 'text-green-600');
                counterEl.classList.add('text-red-500');
            } else {
                counterEl.classList.remove('text-green-600', 'text-red-500');
                counterEl.classList.add('text-zinc-500');
            }
        }
    }

    inputs.forEach(input => {
        let scanBuffer = '';
        let lastKeyTime = Date.now();

        // Initial update
        updateCounter(input);

        input.addEventListener('input', function() {
            updateCounter(this);
        });

        input.addEventListener('keydown', function(e) {
            const currentTime = Date.now();
            
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submit
                
                // If it was a fast scan (e.g. 15 chars in < 200ms)
                if (scanBuffer.length > 5 && (currentTime - lastKeyTime) < 500) {
                    // Extract last 3 digits of the scanned string
                    let last3 = scanBuffer.slice(-3);
                    
                    // Remove the raw scanned string that was typed into the input
                    let currentValue = this.value;
                    let valueWithoutBuffer = currentValue.slice(0, currentValue.length - scanBuffer.length).trim();
                    
                    // Clean up trailing comma
                    if (valueWithoutBuffer.endsWith(',')) {
                        valueWithoutBuffer = valueWithoutBuffer.slice(0, -1).trim();
                    }
                    
                    // Append the extracted 3 digits
                    if (valueWithoutBuffer) {
                        this.value = valueWithoutBuffer + ', ' + last3 + ', ';
                    } else {
                        this.value = last3 + ', ';
                    }
                }
                
                scanBuffer = '';
                updateCounter(this);
                return;
            }

            // Keystroke tracking for scanner detection
            if (e.key.length === 1) { // Normal character
                if (currentTime - lastKeyTime > 100) { // Slow typing = human
                    scanBuffer = e.key;
                } else { // Fast typing = scanner
                    scanBuffer += e.key;
                }
            } else {
                scanBuffer = ''; // Reset on backspace, shift, etc.
            }
            
            lastKeyTime = currentTime;
        });

        // Handle Paste event for bulk manual input
        input.addEventListener('paste', function(e) {
            // setTimeout to wait for the value to actually be inserted
            setTimeout(() => {
                updateCounter(this);
            }, 10);
        });
    });
});
</script>
@endpush
