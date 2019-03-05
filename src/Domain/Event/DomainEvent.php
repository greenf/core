<?php namespace Greenf\Core\Domain\Event;

use Greenf\Core\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use RuntimeException;

abstract class DomainEvent
{
    private $messageId;
    private $eventId;
    private $aggregateId;
    private $data;
    private $occurredOn;

    public function __construct(
        string $aggregateId,
        array $data = [],
        string $eventId = null,
        string $occurredOn = null
    ) {
        $eventId = $eventId ?: Uuid::random()->value();

        $this->messageId = new Uuid($eventId);

        $this->eventId = $eventId;
        $this->guardAggregateId($aggregateId);
        //DomainEventGuard::guard($data, $this->rules(), get_called_class());

        $this->aggregateId = $aggregateId;
        $this->data        = $data;
        $this->occurredOn  = $occurredOn ?: date_to_string(new DateTimeImmutable());
    }

    abstract protected function rules(): array;

    abstract public static function eventName(): string;

    public function messageType(): string
    {
        return 'domain_event';
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function aggregateId()
    {
        return $this->aggregateId;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }

    public function __call($method, $args)
    {
        dump('event called');

        $attributeName = $method;
        if (0 === strpos($method, 'is')) {
            $attributeName = lcfirst(substr($method, 2));
        }

        if (0 === strpos($method, 'has')) {
            $attributeName = lcfirst(substr($method, 3));
        }

        if (isset($this->data[$attributeName])) {
            return $this->data[$attributeName];
        }

        throw new RuntimeException(sprintf('The method "%s" does not exist.', $method));
    }

    private function guardAggregateId($aggregateId)
    {
        if (!is_string($aggregateId) && !is_int($aggregateId)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The Aggregate Id <%s> in <%s> is not valid, should be int or string.',
                    var_export($aggregateId, true),
                    get_class($this)
                )
            );
        }
    }
}

function date_to_string(DateTimeInterface $date): string
{
    $timestamp             = $date->getTimestamp();
    $microseconds          = $date->format('u');
    $millisecondsOnASecond = 1000;

    return (string) (((float) ((string) $timestamp . '.' . (string) $microseconds)) * $millisecondsOnASecond);
}