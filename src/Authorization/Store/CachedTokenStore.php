<?php

namespace CthulhuDen\Portainer\Authorization\Store;

use Psr\SimpleCache\CacheInterface;

class CachedTokenStore implements TokenStoreInterface
{
    private $cache;
    private $key;

    public function __construct(CacheInterface $cache, string $key)
    {
        $this->cache = $cache;
        $this->key = $key;
    }

    public function getToken(): ?string
    {
        /** @var mixed $token */
        $token = $this->cache->get($this->key);
        if (is_string($token) && $token) {
            return $token;
        }

        return null;
    }

    public function setToken(string $token): void
    {
        $this->cache->set($this->key, $token);
    }
}
