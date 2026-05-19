<?php

namespace App\Domain\ValueObject;

enum UserRole: string
{
    case Artist = 'ROLE_ARTIST';
    case Admin = 'ROLE_ADMIN';
    case User = 'ROLE_USER';

    public function publicValue(): string
    {
        return match ($this) {
            self::User => 'user',
            self::Artist => 'artist',
            self::Admin => 'admin',
        };
    }
}
