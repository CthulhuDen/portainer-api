<?php

namespace CthulhuDen\Portainer\Authorization\Store;

class SimpleTokenStore implements TokenStoreInterface
{
    /** @var string|null */
    private $token = null;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
