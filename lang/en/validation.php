<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi
    |--------------------------------------------------------------------------
    */

    'accepted' => ':attribute harus diterima.',
    'active_url' => ':attribute bukan URL yang valid.',
    'after' => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'array' => ':attribute harus berupa data array.',
    'before' => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => ':attribute harus memiliki antara :min sampai :max item.',
        'file' => 'Ukuran :attribute harus antara :min sampai :max kilobyte.',
        'numeric' => ':attribute harus bernilai antara :min sampai :max.',
        'string' => ':attribute harus terdiri dari :min sampai :max karakter.',
    ],
    'boolean' => ':attribute harus bernilai benar atau salah.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Password yang dimasukkan tidak sesuai.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_equals' => ':attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => 'Format :attribute harus sesuai dengan :format.',
    'decimal' => ':attribute harus memiliki :decimal angka desimal.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => ':attribute harus terdiri dari :digits angka.',
    'digits_between' => ':attribute harus terdiri dari :min sampai :max angka.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'ends_with' => ':attribute harus diakhiri dengan salah satu nilai berikut: :values.',
    'exists' => ':attribute yang dipilih tidak ditemukan.',
    'file' => ':attribute harus berupa file.',
    'filled' => ':attribute wajib diisi.',
    'gt' => [
        'array' => ':attribute harus memiliki lebih dari :value item.',
        'file' => 'Ukuran :attribute harus lebih dari :value kilobyte.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string' => ':attribute harus memiliki lebih dari :value karakter.',
    ],
    'gte' => [
        'array' => ':attribute harus memiliki minimal :value item.',
        'file' => 'Ukuran :attribute harus lebih besar atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus lebih besar atau sama dengan :value.',
        'string' => ':attribute harus memiliki minimal :value karakter.',
    ],
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'ip' => ':attribute harus berupa alamat IP yang valid.',
    'json' => ':attribute harus berupa data JSON yang valid.',
    'lowercase' => ':attribute harus menggunakan huruf kecil.',
    'lt' => [
        'array' => ':attribute harus memiliki kurang dari :value item.',
        'file' => 'Ukuran :attribute harus kurang dari :value kilobyte.',
        'numeric' => ':attribute harus kurang dari :value.',
        'string' => ':attribute harus memiliki kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => ':attribute tidak boleh memiliki lebih dari :value item.',
        'file' => 'Ukuran :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'string' => ':attribute tidak boleh memiliki lebih dari :value karakter.',
    ],
    'max' => [
        'array' => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file' => 'Ukuran :attribute tidak boleh lebih dari :max kilobyte.',
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'mimes' => ':attribute harus berupa file dengan format: :values.',
    'mimetypes' => ':attribute harus berupa file dengan tipe: :values.',
    'min' => [
        'array' => ':attribute minimal harus memiliki :min item.',
        'file' => 'Ukuran :attribute minimal :min kilobyte.',
        'numeric' => ':attribute minimal harus bernilai :min.',
        'string' => ':attribute minimal harus terdiri dari :min karakter.',
    ],
    'not_in' => ':attribute yang dipilih tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'password' => [
        'letters' => ':attribute harus memiliki minimal satu huruf.',
        'mixed' => ':attribute harus memiliki minimal satu huruf besar dan satu huruf kecil.',
        'numbers' => ':attribute harus memiliki minimal satu angka.',
        'symbols' => ':attribute harus memiliki minimal satu simbol.',
        'uncompromised' => ':attribute yang digunakan pernah mengalami kebocoran data. Gunakan password lain.',
    ],
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute wajib diisi.',
    'required_if' => ':attribute wajib diisi ketika :other bernilai :value.',
    'required_unless' => ':attribute wajib diisi kecuali :other bernilai :values.',
    'required_with' => ':attribute wajib diisi ketika :values tersedia.',
    'required_without' => ':attribute wajib diisi ketika :values tidak tersedia.',
    'same' => ':attribute dan :other harus sama.',
    'size' => [
        'array' => ':attribute harus memiliki :size item.',
        'file' => 'Ukuran :attribute harus sebesar :size kilobyte.',
        'numeric' => ':attribute harus bernilai :size.',
        'string' => ':attribute harus terdiri dari :size karakter.',
    ],
    'starts_with' => ':attribute harus diawali dengan salah satu nilai berikut: :values.',
    'string' => ':attribute harus berupa teks.',
    'unique' => ':attribute sudah digunakan.',
    'uploaded' => ':attribute gagal diunggah.',
    'uppercase' => ':attribute harus menggunakan huruf besar.',
    'url' => ':attribute harus berupa URL yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Pesan Khusus Setiap Field
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'password' => [
            'confirmed' => 'Password tidak cocok',
            'min' => 'Password minimal harus terdiri dari :min karakter.',
        ],

        'email' => [
            'unique' => 'Email tersebut sudah digunakan oleh akun lain.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Nama Field
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name' => 'nama pengguna',
        'email' => 'email',
        'phone' => 'nomor WhatsApp',
        'store_name' => 'nama toko',
        'address' => 'alamat',
        'role' => 'hak akses',
        'password' => 'password',
        'password_confirmation' => 'konfirmasi password',

        'title' => 'judul',
        'content' => 'isi artikel',
        'thumbnail' => 'thumbnail',
        'status' => 'status',

        'product_name' => 'nama produk',
        'type' => 'tipe produk',
        'color' => 'warna',
        'capacity' => 'kapasitas',
        'price' => 'harga',
        'stock' => 'stok',
        'images' => 'foto produk',
        'images.*' => 'foto produk',
    ],

];