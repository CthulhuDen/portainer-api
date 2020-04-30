<?php

namespace CthulhuDen\Portainer\Model;

/**
 * @property-read ContainerSpec $ContainerSpec
 */
class TaskTemplate extends AbstractModel
{
    protected static function getCasts(): array
    {
        return [
            'ContainerSpec' => ContainerSpec::class,
        ];
    }
}
