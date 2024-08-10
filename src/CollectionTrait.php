<?php

namespace ICanBoogie\CLDR;

use LogicException;

/**
 * A trait for classes implementing collection.
 */
trait CollectionTrait
{
    /**
     * @param string $offset
     * @param mixed $value
     *
     * @throw LogicException in an attempt to set the offset.
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new LogicException("Offset '$offset' is not writable");
    }

    /**
     * @throw LogicException in an attempt to unset the offset.
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new LogicException("Offset '$offset' is not writable");
    }
}
