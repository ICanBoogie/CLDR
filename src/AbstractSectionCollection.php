<?php

namespace ICanBoogie\CLDR;

use ArrayAccess;
use LogicException;

/**
 * @template TKey of array-key
 *
 * @implements ArrayAccess<TKey, mixed>
 */
abstract class AbstractSectionCollection implements ArrayAccess
{
    use CollectionTrait;

    public function __construct(
        public readonly Repository $repository
    ) {
    }

    abstract public function offsetExists(mixed $offset): bool;

    /**
     * @var array<string, array>
     *     Loaded sections, where _key_ is a section name and _value_ its data.
     *
     * @phpstan-ignore-next-line
     */
    private array $sections = [];

    /**
     * @throws LogicException
     * @throws ResourceNotFound
     */
    #[\ReturnTypeWillChange]
    public function offsetGet(mixed $offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new LogicException("Offset '$offset' does not exist");
        }

        return $this->sections[$offset] ??= $this->repository->fetch(
            $this->path_for($offset),
            $this->data_path_for($offset)
        );
    }

    /**
     * Returns the CLDR path for the offset.
     */
    abstract protected function path_for(string $offset): string;

    /**
     * Returns the data path for the offset.
     */
    abstract protected function data_path_for(string $offset): string;
}
