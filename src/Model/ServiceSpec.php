<?php

namespace CthulhuDen\Portainer\Model;

/**
 * @property-read ServiceSpecMode $Mode
 * @property-read string $Name
 */
class ServiceSpec extends AbstractModel
{
    protected static function getCasts(): array
    {
        return [
            'Mode' => ServiceSpecMode::class,
        ];
    }
}
