<?php namespace Greenf\Core\Domain\ValueObject;

use Greenf\Core\Domain\ValueObject\Exception\InvalidDatetimeException;
use PhpValueObjects\AbstractValueObject;

class Datetime extends AbstractValueObject
{
    /**
     * @param mixed $value
     *
     * @throws InvalidDatetimeException
     */
    protected function guard($value)
    {
        if (false === is_bool($value)) {
            throw new InvalidDatetimeException($value);
        }
    }
}


