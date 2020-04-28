<?php

namespace CthulhuDen\Portainer\Model;

/**
 * @property-read string $State
 * @property-read TaskContainerStatus $ContainerStatus
 */
class TaskStatus extends AbstractModel
{
    const STATE_RUNNING = 'running';

    protected static function getCasts(): array
    {
        return [
            'ContainerStatus' => TaskContainerStatus::class,
        ];
    }
}
