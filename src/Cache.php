<?php

namespace ICanBoogie\CLDR;

interface Cache
{
    /**
     * @return array<string, mixed>|null
     */
    public function get(string $path): ?array;

    /**
     * @param array<string, mixed> $data
     */
    public function set(string $path, array $data): void;
}
