<?php

namespace CthulhuDen\Portainer\Authorization\Store;

interface TokenStoreInterface
{
    public function getToken(): ?string;

    public function setToken(string $token): void;
}
