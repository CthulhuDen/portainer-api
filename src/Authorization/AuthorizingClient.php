<?php

namespace CthulhuDen\Portainer\Authorization;

use CthulhuDen\Portainer\Authorization\Exception\AuthorizationException;
use CthulhuDen\Portainer\Authorization\Store\TokenStoreInterface;
use Nyholm\Psr7\Stream;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthorizingClient implements ClientInterface
{
    private $inner;
    private $tokenStore;
    private $requestFactory;
    private $username;
    private $password;

    public function __construct(
        ClientInterface $inner,
        TokenStoreInterface $tokenStore,
        RequestFactoryInterface $requestFactory,
        string $username,
        string $password
    ) {
        $this->inner = $inner;
        $this->tokenStore = $tokenStore;
        $this->requestFactory = $requestFactory;
        $this->username = $username;
        $this->password = $password;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if (($token = $this->tokenStore->getToken()) !== null) {
            $response = $this->inner->sendRequest($request->withHeader('Authorization', "Bearer {$token}"));

            if ($response->getStatusCode() !== 401) {
                return $response;
            }
        }

        $token = $this->getFreshToken($request);

        return $this->inner->sendRequest($request->withHeader('Authorization', "Bearer {$token}"));
    }

    private function getFreshToken(RequestInterface $request): string
    {
        $uri = $request->getUri()
            ->withPath('/api/auth')
            ->withQuery('')
            ->withFragment('');

        $authRequest = $this->requestFactory->createRequest('POST', $uri)
            ->withHeader('Content-type', 'application/json')
            ->withBody(Stream::create(json_encode([
                'Username' => $this->username,
                'Password' => $this->password,
            ])));

        $authResponse = $this->inner->sendRequest($authRequest);
        if ($authResponse->getStatusCode() !== 200) {
            throw new AuthorizationException(
                "Non-200 response code: {$authResponse->getStatusCode()}",
                $authResponse,
            );
        }

        /** @var array{jwt:string} $data */
        $data = json_decode((string) $authResponse->getBody(), true);
        $this->tokenStore->setToken($token = $data['jwt']);

        return $token;
    }
}
