@extends('layouts.admin')

@section('title', 'Pesan Kontak')
@section('heading', 'Pesan Kontak')
@section('eyebrow', 'Superadmin')

@section('content')
    <div class="table-shell">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pengirim</th>
                        <th>Subjek</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                        <tr @class(['bg-blue-50/50' => ! $message->is_read])>
                            <td>
                                <strong>{{ $message->name }}</strong>
                                <div class="meta-text">{{ $message->email }}</div>
                            </td>
                            <td>{{ $message->subject }}</td>
                            <td class="text-zinc-500">
                                {{ $message->created_at->format('d M Y H:i') }}
                            </td>
                            <td>
                                <span
                                    @class([
                                        'badge',
                                        'badge-neutral' => $message->is_read,
                                        'badge-info' => ! $message->is_read,
                                    ])
                                >
                                    {{ $message->is_read ? 'Dibaca' : 'Baru' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a
                                    class="text-link"
                                    href="{{ route('admin.contacts.show', $message) }}"
                                >
                                    Buka
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">Belum ada pesan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $messages->links() }}</div>
@endsection
