<?php namespace Greenf\Core\Domain\ValueObject;

class Uuid extends \PhpValueObjects\Identity\Uuid {

    public static function random(): self
    {
        return new self((string) \Ramsey\Uuid\Uuid::uuid4());
    }
}