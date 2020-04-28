<?php

namespace CthulhuDen\Portainer;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait BuildsAndSendsRequests
{
    /** @var ClientInterface */
    private $http;
    /** @var RequestFactoryInterface */
    private $requestFactory;
    /** @var string */
    private $endpoint;

    private function buildRequest(string $method, string $path): RequestInterface
    {
        $path = ltrim($path, '/');

        return $this->requestFactory->createRequest($method, "{$this->endpoint}/{$path}");
    }

    private function sendAndExpect2xx(RequestInterface $request): ResponseInterface
    {
        $response = $this->http->sendRequest($request);

        if ($response->getStatusCode() <= 299) {
            return $response;
        }

        throw new InvalidResponseException("Non-2xx response code: {$response->getStatusCode()}", $response);
    }
}
