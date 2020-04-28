<?php

namespace CthulhuDen\Portainer;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class Client
{
    use BuildsAndSendsRequests;

    public function __construct(
        ClientInterface $http,
        RequestFactoryInterface $requestFactory,
        string $endpoint
    ) {
        $this->http = $http;
        $this->requestFactory = $requestFactory;
        $this->endpoint = rtrim($endpoint, '/');
    }

    public function endpoint(int $endpoint): EndpointScopedClient
    {
        return new EndpointScopedClient($this->http, $this->requestFactory, $this->endpoint, $endpoint);
    }
}
