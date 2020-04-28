<?php

namespace CthulhuDen\Portainer\Model;

/**
 * @property-read string $ID
 * @property-read ServiceSpec $Spec
 */
class Service extends AbstractModel
{
    protected static function getCasts(): array
    {
        return [
            'Spec' => ServiceSpec::class,
        ];
    }
}
