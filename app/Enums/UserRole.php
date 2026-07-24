<?php

namespace App\Enums;

enum UserRole: string
{
    case RESSELLER = 'resseller';
    case ADMIN = 'admin';
    case SUPERADMIN = 'superadmin';

    public function label(): string
    {
        return match ($this) {
            self::RESSELLER => 'Reseller',
            self::ADMIN => 'Admin',
            self::SUPERADMIN => 'Superadmin',
        };
    }
}
