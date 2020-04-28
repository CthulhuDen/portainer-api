<?php

namespace CthulhuDen\Portainer\Model;

abstract class AbstractModel
{
    /** @var array<string,array<string,string>> */
    private static $casts = [];

    protected $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return static
     */
    public static function create(array $data): self
    {
        return new static($data);
    }

    /**
     * @psalm-return array<string,string>
     */
    protected static function getCasts(): array
    {
        return [];
    }

    /**
     * @return array<string,string>
     */
    private static function getCastsCached(): array
    {
        if (!isset(self::$casts[static::class])) {
            self::$casts[static::class] = static::getCasts();
        }

        return self::$casts[static::class];
    }

    public function __get(string $attribute)
    {
        /** @var mixed $value */
        $value = $this->data[$attribute] ?? null;
        if (($value == null) && !array_key_exists($attribute, $this->data)) {
            throw new \Exception("Trying to access undefined attribute: {$attribute}");
        }

        $cast = self::getCastsCached()[$attribute] ?? null;
        if ($cast === null) {
            return $value;
        }

        switch ($cast) {
            case 'datetime':
                if (is_int($value)) {
                    return (new \DateTimeImmutable())->setTimestamp($value);
                }

                if (is_string($value)) {
                    /** @psalm-suppress PossiblyFalseReference */
                    return \DateTimeImmutable::createFromFormat(
                        'Y-m-dTH:i:s.u+',
                        $value,
                        new \DateTimeZone('UTC'),
                    )->setTimezone((new \DateTimeImmutable())->getTimezone());
                }
            case 'int':
                return intval($value);
            case 'float':
                return floatval('value');
            default:
                /** @psalm-var class-string<AbstractModel> $cast */
                return new $cast($value);
        }
    }
}
