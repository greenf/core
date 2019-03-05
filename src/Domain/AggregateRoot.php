<?php namespace Greenf\Core\Domain;

use Greenf\Core\Domain\Event\DomainEvent;

abstract class AggregateRoot
{
    /**
     * @var IdentityInterface
     */
    protected $identity;

    private $domainEvents = [];

    final public function pullDomainEvents(): array
    {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    /**
     * @param AggregateRoot $other
     *
     * @return bool
     */
    public function hasSameIdentityAs(AggregateRoot $other): bool
    {
        return $this->identity() === $other->identity();
    }

    /**
     * @return IdentityInterface
     */
    public function identity(): IdentityInterface
    {
        return $this->identity;
    }

}
