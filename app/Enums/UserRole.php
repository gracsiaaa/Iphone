<?php

namespace App\Enums;

enum UserRole: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case SUPERADMIN = 'superadmin';

    public function label(): string
    {
        return match ($this) {
            self::USER => 'Reseller',
            self::ADMIN => 'Admin',
            self::SUPERADMIN => 'Superadmin',
        };
    }
}
