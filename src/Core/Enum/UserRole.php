<?php

namespace App\Core\Enum;

final class UserRole
{
    public const ADMIN = 'ROLE_ADMIN';
    public const MEMBER = 'ROLE_MEMBER';

    /**
     * Returns all valid roles
     */
    public static function choices(): array
    {
        return [
            self::ADMIN,
            self::MEMBER,
        ];
    }
}
