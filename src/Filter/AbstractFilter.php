<?php

namespace CthulhuDen\Portainer\Filter;

abstract class AbstractFilter implements \JsonSerializable
{
    /**
     * @var array<string,string[]>
     * @psalm-var array<string,list<string>>
     */
    private $data = [];

    private function __construct() {}

    /**
     * @return static
     */
    public static function any(): self
    {
        return new static();
    }

    /**
     * @psalm-param list<string> $arguments
     */
    public function __call(string $name, array $arguments): self
    {
        $pieces = preg_split('/(?=[A-Z])/', $name);
        $intro = array_shift($pieces);

        switch ($intro) {
            case 'with':
                $this->data[implode('-', array_map('lcfirst', $pieces))] = $arguments;
                break;
            default:
                throw new \Exception("Unknown function call: {$name}");
        }

        return $this;
    }

    public static function __callStatic(string $name, array $arguments): self
    {
        $self = new static();
        $self->{$name}(...$arguments);

        return $self;
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
