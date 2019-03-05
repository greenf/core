<?php namespace Greenf\Core\Domain\ValueObject\Exception;

class InvalidDatetimeException extends \Exception
{
    /**
     * InvalidIntegerException constructor.
     *
     * @param string $value
     */
    public function __construct($value)
    {
        parent::__construct(sprintf('"%s" is not a valid datetime.', $value));
    }
}
