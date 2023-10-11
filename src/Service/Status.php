<?php

namespace App\Service;

final class Status
{
    // USER STATUS
    public const PUBLIC = 'published';
    public const PENDING = 'pending';
    public const PRIVATE = 'private';

    // ADMIN STATUS
    public const DELETED = 'deleted';


    public const READABLE_STATUS = [
        self::PUBLIC => 'Public',
        self::PENDING => 'Non-répertorié',
        self::PRIVATE => 'Privé',
        self::DELETED => 'Supprimé',
    ];
}