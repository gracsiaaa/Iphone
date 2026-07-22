@props(['status'])

@php
    $classes = match ($status) {
        \App\Enums\OrderStatus::PENDING_PAYMENT => 'badge-warning',
        \App\Enums\OrderStatus::WAITING_VERIFICATION => 'badge-info',
        \App\Enums\OrderStatus::PAID,
        \App\Enums\OrderStatus::COMPLETED => 'badge-success',
        \App\Enums\OrderStatus::CANCELLED,
        \App\Enums\OrderStatus::REJECTED => 'badge-danger',
    };
@endphp

<span {{ $attributes->class(['badge', $classes]) }}>
    {{ $status->label() }}
</span>
