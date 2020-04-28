<?php

namespace CthulhuDen\Portainer\Model;

/**
 * @property-read ServiceReplication $Replicated
 */
class ServiceSpecMode extends AbstractModel
{
    protected static function getCasts(): array
    {
        return [
            'Replicated' => ServiceReplication::class,
        ];
    }
}
