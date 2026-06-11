<?php

namespace App\Enums;

enum UserRole: string
{
    case Developer = 'developer';
    case Admin = 'admin';
    case Teacher = 'teacher';
    case Student = 'student';

    public function label(): string
    {
        return match ($this) {
            self::Developer => 'Developer',
            self::Admin => 'Admin',
            self::Teacher => 'O\'qituvchi',
            self::Student => 'O\'quvchi',
        };
    }

    public static function assignableOnRegister(): array
    {
        return [self::Student->value];
    }

    public static function assignableByAdmin(): array
    {
        return [self::Admin->value, self::Teacher->value, self::Student->value];
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
