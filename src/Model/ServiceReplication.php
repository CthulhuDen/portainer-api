<?php

namespace CthulhuDen\Portainer\Model;

/**
 * @property-read int $Replicas
 */
class ServiceReplication extends AbstractModel
{
    protected static function getCasts(): array
    {
        return [
            'Replicas' => 'int',
        ];
    }
}
