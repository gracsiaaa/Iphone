@extends('layouts.admin')

@section('title', 'Activity Log')
@section('heading', 'Activity Log')
@section('eyebrow', 'Superadmin audit trail')

@section('content')
    <div class="table-shell">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap text-zinc-500">
                                {{ $log->created_at->format('d M Y H:i:s') }}
                            </td>
                            <td>{{ $log->user?->email ?: 'System' }}</td>
                            <td>
                                <code class="rounded-lg bg-zinc-100 px-2 py-1 text-xs">
                                    {{ $log->action }}
                                </code>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td class="text-zinc-500">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">Belum ada activity log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $logs->links() }}</div>
@endsection
