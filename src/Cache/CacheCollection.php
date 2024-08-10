<?php

namespace ICanBoogie\CLDR\Cache;

use ICanBoogie\CLDR\Cache;

/**
 * A collection of {@see Cache} instances.
 */
final class CacheCollection implements Cache
{
    /**
     * @param Cache[] $collection
     */
    public function __construct(
        private readonly array $collection
    ) {
    }

    public function get(string $path): ?array
    {
        foreach ($this->collection as $cache) {
            $data = $cache->get($path);

            if ($data !== null) {
                return $data;
            }
        }

        return null;
    }

    public function set(string $path, array $data): void
    {
        foreach ($this->collection as $cache) {
            $cache->set($path, $data);
        }
    }
}
