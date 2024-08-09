<?php

namespace ICanBoogie\CLDR;

use ICanBoogie\OffsetNotWritable;

/**
 * A trait for classes implementing collection.
 */
trait CollectionTrait
{
    /**
     * @param string $offset
     * @param mixed $value
     *
     * @throw OffsetNotWritable in attempt to set the offset.
     */
    public function offsetSet($offset, $value): void
    {
        throw new OffsetNotWritable(offset: $offset, container: $this);
    }

    /**
     * @param string $offset
     *
     * @throw OffsetNotWritable in attempt to unset the offset.
     */
    public function offsetUnset($offset): void
    {
        throw new OffsetNotWritable(offset: $offset, container: $this);
    }
}
