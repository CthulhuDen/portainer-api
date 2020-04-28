<?php

namespace CthulhuDen\Portainer\Model;

/**
 * @property-read string $ID
 * @property-read string $ServiceID
 * @property-read \DateTimeImmutable $CreatedAt
 * @property-read TaskStatus $Status
 */
class Task extends AbstractModel
{
    protected static function getCasts(): array
    {
        return [
            'CreatedAt' => 'datetime',
            'Status' => TaskStatus::class,
        ];
    }
}
