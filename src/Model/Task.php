<?php

namespace CthulhuDen\Portainer\Model;

/**
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
