<?php namespace Greenf\Core\Domain\Event;

interface DomainEventSubscriber
{
    public static function subscribedTo(): array;
}
