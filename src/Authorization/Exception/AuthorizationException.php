<?php

namespace CthulhuDen\Portainer\Authorization\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class AuthorizationException extends \Exception
{
    /** @var ResponseInterface */
    private $response;

    public function __construct(string $details, ResponseInterface $response, Throwable $previous = null)
    {
        $this->response = $response;

        parent::__construct("Failed to authorize request: {$details}", 0, $previous);
    }

    public function __toString(): string
    {
        return parent::__toString() . "\nResponse body:\n" . $this->response->getBody();
    }
}
