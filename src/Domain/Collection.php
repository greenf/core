<?php namespace Greenf\Core\Domain;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;

abstract class Collection implements Countable, IteratorAggregate, ArrayAccess
{
    /**
     * An array containing the entries of this collection.
     *
     * @var array
     */
    protected $items;

    /**
     * Initializes a new ArrayCollection.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Creates a new instance from the specified items.
     *
     * This method is provided for derived classes to specify how a new
     * instance should be created when constructor semantics have changed.
     *
     * @param array $items items.
     *
     * @return static
     */
    protected function createFrom(array $items)
    {
        return new static($items);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     */
    public function first()
    {
        return reset($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function last()
    {
        return end($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        if ( ! isset($this->items[$key]) && ! array_key_exists($key, $this->items)) {
            return null;
        }

        $removed = $this->items[$key];
        unset($this->items[$key]);

        return $removed;
    }

    /**
     * {@inheritDoc}
     */
    public function removeElement($element)
    {
        $key = array_search($element, $this->items, true);

        if ($key === false) {
            return false;
        }

        unset($this->items[$key]);

        return true;
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if ( ! isset($offset)) {
            $this->add($value);
            return;
        }

        $this->set($offset, $value);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function containsKey($key)
    {
        return isset($this->items[$key]) || array_key_exists($key, $this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function contains($element)
    {
        return in_array($element, $this->items, true);
    }

    /**
     * {@inheritDoc}
     */
    public function exists(Closure $p)
    {
        foreach ($this->items as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function indexOf($element)
    {
        return array_search($element, $this->items, true);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        return $this->items[$key] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function getKeys()
    {
        return array_keys($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function getValues()
    {
        return array_values($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function add($element)
    {
        $this->items[] = $element;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function map(Closure $func)
    {
        return $this->createFrom(array_map($func, $this->items));
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public function filter(Closure $p)
    {
        return $this->createFrom(array_filter($this->items, $p));
    }

    /**
     * {@inheritDoc}
     */
    public function forAll(Closure $p)
    {
        foreach ($this->items as $key => $element) {
            if ( ! $p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function partition(Closure $p)
    {
        $matches = $noMatches = [];

        foreach ($this->items as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return [$this->createFrom($matches), $this->createFrom($noMatches)];
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . '@' . spl_object_hash($this);
    }

    /**
     * {@inheritDoc}
     */
    public function clear()
    {
        $this->items = [];
    }

    /**
     * {@inheritDoc}
     */
    public function slice($offset, $length = null)
    {
        return array_slice($this->items, $offset, $length, true);
    }

    public function value(): array
    {
        return array_map(function ($item) {
            return $item;
        }, $this->getValues());
    }
}
