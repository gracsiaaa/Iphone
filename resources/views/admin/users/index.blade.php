@extends('layouts.admin')

@section('title', 'Pengguna')
@section('heading', 'Pengguna')
@section('eyebrow', 'Superadmin access control')

@section('content')
    <div class="mb-6 flex flex-wrap justify-between gap-4">
        <form>
            <select class="input" name="role" onchange="this.form.submit()">
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
                                <div class="meta-text">{{ $user->email }}</div>
                            </td>
                            <td>
                                {{ $user->store_name ?: '-' }}
                                <div class="meta-text">{{ $user->phone }}</div>
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
                            <td class="text-right">
                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="text-link"
                                >
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="table-empty">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
@endsection
