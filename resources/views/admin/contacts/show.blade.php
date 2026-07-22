@extends('layouts.admin')

@section('title', 'Pesan Kontak')
@section('heading', $message->subject)
@section('eyebrow', 'Pesan dari '.$message->name)

@section('content')
    <section class="surface max-w-4xl p-7 sm:p-8">
        <div class="grid gap-5 border-b border-zinc-200 pb-6 text-sm sm:grid-cols-3">
            <div>
                <span class="info-label">Nama</span>
                <div class="info-value">{{ $message->name }}</div>
            </div>
            <div>
                <span class="info-label">Email</span>
                <div class="info-value">{{ $message->email }}</div>
            </div>
            <div>
                <span class="info-label">Telepon</span>
                <div class="info-value">{{ $message->phone ?: '-' }}</div>
            </div>
        </div>

        <div class="whitespace-pre-line py-7 leading-8 text-zinc-700">
            {{ $message->message }}
        </div>

        <form
            action="{{ route('admin.contacts.destroy', $message) }}"
            method="POST"
            onsubmit="return confirm('Hapus pesan ini?')"
        >
            @csrf
            @method('DELETE')
            <button class="btn-danger">Hapus Pesan</button>
        </form>
    </section>
@endsection
