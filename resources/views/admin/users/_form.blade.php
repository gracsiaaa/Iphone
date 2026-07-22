<div class="surface mx-auto max-w-4xl form-grid p-7 sm:p-8">
    <div class="field">
        <label class="label" for="name">Nama</label>
        <input
            id="name"
            class="input"
            name="name"
            value="{{ old('name', $user->name ?? '') }}"
            required
        >
    </div>

    <div class="field">
        <label class="label" for="email">Email</label>
        <input
            id="email"
            class="input"
            type="email"
            name="email"
            value="{{ old('email', $user->email ?? '') }}"
            required
        >
    </div>

    <div class="field">
        <label class="label" for="phone">Nomor WhatsApp</label>
        <input
            id="phone"
            class="input"
            name="phone"
            value="{{ old('phone', $user->phone ?? '') }}"
        >
    </div>

    <div class="field">
        <label class="label" for="store-name">Nama toko</label>
        <input
            id="store-name"
            class="input"
            name="store_name"
            value="{{ old('store_name', $user->store_name ?? '') }}"
        >
    </div>

    <div class="field">
        <label class="label" for="role">Role</label>
        <select id="role" class="input" name="role" required>
            @foreach(\App\Enums\UserRole::cases() as $role)
                <option
                    value="{{ $role->value }}"
                    @selected(
                        old('role', isset($user) ? $user->role->value : 'user')
                        === $role->value
                    )
                >
                    {{ $role->label() }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="field flex items-end">
        <label class="check-row mb-3">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $user->is_active ?? true))
            >
            <span>Akun aktif</span>
        </label>
    </div>

    <div class="field field-full">
        <label class="label" for="address">Alamat</label>
        <textarea
            id="address"
            class="input min-h-24"
            name="address"
        >{{ old('address', $user->address ?? '') }}</textarea>
    </div>

    <div class="field">
        <label class="label" for="password">
            Password
            @isset($user)
                <span class="font-normal text-zinc-400">(kosongkan jika tidak diubah)</span>
            @endisset
        </label>
        <input
            id="password"
            class="input"
            type="password"
            name="password"
            {{ isset($user) ? '' : 'required' }}
        >
    </div>

    <div class="field">
        <label class="label" for="password-confirmation">Konfirmasi password</label>
        <input
            id="password-confirmation"
            class="input"
            type="password"
            name="password_confirmation"
            {{ isset($user) ? '' : 'required' }}
        >
    </div>

    <div class="field-full form-actions">
        <button class="btn-primary">Simpan Pengguna</button>
    </div>
</div>
