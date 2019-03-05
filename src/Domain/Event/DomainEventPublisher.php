<?php namespace Greenf\Core\Domain\Event;

interface DomainEventPublisher
{
    /**
     * Records events to be published afterwards using the publishRecorded method
     */
    public function record(DomainEvent ...$domainEvents): void;

    /**
     * Publishes previously recorded events
     */
    public function publishRecorded(): void;

    /**
     * Immediately publishes the received events
     */
    public function publish(DomainEvent ...$domainEvents);
}
