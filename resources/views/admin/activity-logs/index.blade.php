@extends('layouts.admin')

@section('title', 'Activity Log')
@section('heading', 'Activity Log')
@section('eyebrow', 'Audit trail sistem')

@section('content')
    <div class="space-y-6">
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="surface panel-padding">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-zinc-400">Aktivitas Hari Ini</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-zinc-950">{{ number_format($stats['today']) }}</p>
                        <p class="mt-1 text-xs text-zinc-500">Semua event yang tercatat hari ini</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-zinc-950 text-white">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8">
                            <path d="M9 11l3 3L22 4" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="surface panel-padding">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-zinc-400">Login Hari Ini</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-zinc-950">{{ number_format($stats['login']) }}</p>
                        <p class="mt-1 text-xs text-zinc-500">Login berhasil ke sistem</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 17l5-5-5-5M15 12H3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="surface panel-padding">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-zinc-400">Approval Hari Ini</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-zinc-950">{{ number_format($stats['approved']) }}</p>
                        <p class="mt-1 text-xs text-zinc-500">Pembayaran yang disetujui admin</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8">
                            <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="surface panel-padding">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-zinc-400">Reject Hari Ini</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-zinc-950">{{ number_format($stats['rejected']) }}</p>
                        <p class="mt-1 text-xs text-zinc-500">Pembayaran yang ditolak admin</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-red-50 text-red-700">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8">
                            <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <section class="surface overflow-hidden">
            <div class="border-b border-zinc-200 px-4 py-5 sm:px-6">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
                    <div>
                        <h2 class="panel-title">Riwayat aktivitas</h2>
                        <p class="mt-1 text-sm text-zinc-500">Pantau login, pesanan, pembayaran, dan tindakan admin secara kronologis.</p>
                    </div>
                    <p class="text-xs font-semibold text-zinc-400">{{ number_format($logs->total()) }} event ditemukan</p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="border-b border-zinc-200 bg-zinc-50/70 p-4 sm:p-6">
                <div class="grid gap-4 xl:grid-cols-12">
                    <div class="xl:col-span-4">
                        <label for="activity-q" class="label">Cari aktivitas</label>
                        <div class="relative">
                            <input
                                id="activity-q"
                                type="search"
                                name="q"
                                value="{{ request('q') }}"
                                class="input !pl-10"
                                placeholder="Nama, email, invoice, aksi, atau IP"
                            >
                        </div>
                    </div>

                    <div class="xl:col-span-2">
                        <label for="activity-category" class="label">Kategori</label>
                        <select id="activity-category" name="category" class="input">
                            <option value="">Semua kategori</option>
                            <option value="auth" @selected(request('category') === 'auth')>Login & akun</option>
                            <option value="order" @selected(request('category') === 'order')>Pesanan</option>
                            <option value="payment" @selected(request('category') === 'payment')>Pembayaran</option>
                        </select>
                    </div>

                    <div class="xl:col-span-2">
                        <label for="activity-role" class="label">Peran pelaku</label>
                        <select id="activity-role" name="role" class="input">
                            <option value="">Semua peran</option>
                            <option value="user" @selected(request('role') === 'user')>Reseller</option>
                            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                            <option value="superadmin" @selected(request('role') === 'superadmin')>Superadmin</option>
                        </select>
                    </div>

                    <div class="xl:col-span-2">
                        <label for="activity-date-from" class="label">Dari tanggal</label>
                        <input id="activity-date-from" type="date" name="date_from" value="{{ request('date_from') }}" class="input">
                    </div>

                    <div class="xl:col-span-2">
                        <label for="activity-date-to" class="label">Sampai tanggal</label>
                        <input id="activity-date-to" type="date" name="date_to" value="{{ request('date_to') }}" class="input">
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary !py-2.5">Terapkan Filter</button>
                    @if(request()->hasAny(['q', 'category', 'role', 'date_from', 'date_to']))
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn-secondary !py-2.5">Reset</a>
                    @endif
                </div>
            </form>

            <div class="divide-y divide-zinc-100">
                @forelse($logs as $log)
                    @php
                        [$iconBg, $iconText, $badgeClass] = match ($log->action) {
                            'auth.login' => ['bg-blue-50', 'text-blue-700', 'bg-blue-50 text-blue-700 ring-blue-100'],
                            'auth.logout' => ['bg-zinc-100', 'text-zinc-700', 'bg-zinc-100 text-zinc-700 ring-zinc-200'],
                            'auth.register' => ['bg-indigo-50', 'text-indigo-700', 'bg-indigo-50 text-indigo-700 ring-indigo-100'],
                            'order.created' => ['bg-violet-50', 'text-violet-700', 'bg-violet-50 text-violet-700 ring-violet-100'],
                            'payment.submitted' => ['bg-amber-50', 'text-amber-700', 'bg-amber-50 text-amber-700 ring-amber-100'],
                            'order.approved' => ['bg-emerald-50', 'text-emerald-700', 'bg-emerald-50 text-emerald-700 ring-emerald-100'],
                            'order.rejected' => ['bg-red-50', 'text-red-700', 'bg-red-50 text-red-700 ring-red-100'],
                            'order.completed' => ['bg-green-50', 'text-green-700', 'bg-green-50 text-green-700 ring-green-100'],
                            default => ['bg-zinc-100', 'text-zinc-700', 'bg-zinc-100 text-zinc-700 ring-zinc-200'],
                        };
                    @endphp

                    <article class="group p-4 transition hover:bg-zinc-50/80 sm:p-6">
                        <div class="grid gap-4 lg:grid-cols-[3rem_minmax(0,1fr)_15rem] lg:gap-5">
                            <div class="hidden lg:block">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $iconBg }} {{ $iconText }} ring-1 ring-inset ring-black/5">
                                    @if($log->action === 'auth.login')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="M10 17l5-5-5-5M15 12H3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @elseif($log->action === 'auth.logout')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @elseif(in_array($log->action, ['order.approved', 'order.completed'], true))
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8"><path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @elseif($log->action === 'order.rejected')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8"><path d="M18 6L6 18M6 6l12 12" stroke-linecap="round"/></svg>
                                    @elseif($log->action === 'payment.submitted')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="1.8"><path d="M6 2h9l5 5v15H6z"/><path d="M14 2v6h6M9 13h6M9 17h6" stroke-linecap="round"/></svg>
                                    @endif
                                </div>
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 ring-inset {{ $badgeClass }}">
                                        {{ $log->action_label }}
                                    </span>
                                    <span class="text-xs font-semibold text-zinc-400">{{ $log->action }}</span>
                                </div>

                                <p class="mt-2 break-words text-sm font-semibold leading-6 text-zinc-900 sm:text-[15px]">
                                    {{ $log->description }}
                                </p>

                                <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-3 text-xs text-zinc-500">
                                    <div class="flex min-w-0 items-center gap-2">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-[11px] font-black uppercase text-white">
                                            {{ $log->user ? mb_substr($log->user->name, 0, 1) : 'S' }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="truncate font-bold text-zinc-800">{{ $log->user?->name ?: 'System' }}</div>
                                            <div class="truncate text-[11px] text-zinc-400">
                                                @if($log->user)
                                                    {{ $log->user->role->label() }} · {{ '@'.$log->user->username }}
                                                @else
                                                    Aktivitas sistem
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($log->subject_label)
                                        <div class="flex items-center gap-1.5">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-4 w-4" stroke-width="1.8"><path d="M6 2h9l5 5v15H6z"/><path d="M14 2v6h6"/></svg>
                                            @if($log->subject instanceof \App\Models\Order)
                                                <a href="{{ route('admin.orders.show', $log->subject) }}" class="font-bold text-blue-600 hover:text-blue-800">
                                                    {{ $log->subject_label }}
                                                </a>
                                            @else
                                                <span class="font-semibold text-zinc-700">{{ $log->subject_label }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-row flex-wrap items-start justify-between gap-4 border-t border-zinc-100 pt-4 lg:flex-col lg:items-end lg:justify-start lg:border-l lg:border-t-0 lg:pl-5 lg:pt-0">
                                <div class="lg:text-right">
                                    <div class="font-bold text-zinc-900">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="mt-0.5 text-sm font-semibold text-zinc-500">{{ $log->created_at->format('H:i:s') }}</div>
                                    <div class="mt-1 text-[11px] text-zinc-400">{{ $log->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="min-w-0 text-xs text-zinc-500 lg:text-right">
                                    <div class="font-mono text-[11px] font-semibold text-zinc-600">{{ $log->ip_address ?: 'IP tidak tersedia' }}</div>
                                    <div class="mt-1 max-w-[15rem] truncate text-[11px] text-zinc-400" title="{{ $log->user_agent }}">
                                        {{ $log->browser_label }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-zinc-100 text-zinc-500">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-6 w-6" stroke-width="1.7"><path d="M6 2h9l5 5v15H6z"/><path d="M14 2v6h6M9 13h6M9 17h4" stroke-linecap="round"/></svg>
                        </div>
                        <h3 class="mt-4 font-bold text-zinc-900">Tidak ada aktivitas ditemukan</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-zinc-500">Coba ubah kata kunci atau filter tanggal, kategori, dan peran pengguna.</p>
                    </div>
                @endforelse
            </div>
        </section>

        @if($logs->hasPages())
            <div>{{ $logs->links() }}</div>
        @endif
    </div>
@endsection
