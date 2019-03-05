<?php namespace Greenf\Core\Domain\Event;

interface EventBus
{
    public function notify(DomainEvent $event): void;
}
