<?php

namespace ICanBoogie\CLDR\Cache;

use ICanBoogie\CLDR\Cache;

final class RuntimeCache implements Cache
{
    /**
     * @var array<string, mixed>
     */
    private array $cache = [];

    /**
     * @inheritDoc
     */
    public function get(string $path): ?array
    {
        return $this->cache[$path] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function set(string $path, array $data): void
    {
        $this->cache[$path] = $data;
    }
}
