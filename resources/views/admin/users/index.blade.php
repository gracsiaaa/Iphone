@extends('layouts.admin')

@section('title', 'Pengguna')
@section('heading', 'Pengguna')
@section('eyebrow', 'Superadmin access control')

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <form method="GET" class="grid gap-3 sm:grid-cols-[260px_180px_auto]">
            <div class="field">
                <label class="label" for="search">Cari pengguna</label>
                <input
                    id="search"
                    class="input"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Nama, username, email, toko"
                >
            </div>

            <div class="field">
                <label class="label" for="role">Role</label>
                <select id="role" class="input" name="role">
                    <option value="">Semua role</option>
                    @foreach(\App\Enums\UserRole::cases() as $role)
                        <option
                            value="{{ $role->value }}"
                            @selected(request('role') === $role->value)
                        >
                            {{ $role->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn-secondary self-end">Filter</button>
        </form>

        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            + Tambah Pengguna
        </a>
    </div>

    <div class="table-shell">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Toko / Telepon</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                <div class="meta-text mt-1">
                                    {{ '@'.$user->username }} · {{ $user->email }}
                                </div>
                            </td>
                            <td>
                                {{ $user->store_name ?: '-' }}
                                <div class="meta-text mt-1">
                                    {{ $user->phone ?: 'Belum ada nomor telepon' }}
                                </div>
                            </td>
                            <td>{{ $user->role->label() }}</td>
                            <td>
                                <span
                                    @class([
                                        'badge',
                                        'badge-success' => $user->is_active,
                                        'badge-danger' => ! $user->is_active,
                                    ])
                                >
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a
                                        href="{{ route('admin.users.edit', $user) }}"
                                        class="text-link"
                                    >
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">
                                Pengguna tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection
