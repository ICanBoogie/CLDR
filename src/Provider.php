<?php

namespace ICanBoogie\CLDR;

use ICanBoogie\CLDR\Provider\ResourceNotFound;

/**
 * An interface for classes that can provide CLDR data.
 */
interface Provider
{
    /**
     * The section path, following the pattern "<identity>/<section>".
     *
     * @return array<string, mixed>
     * @throws ResourceNotFound when the specified path doesn't exist on the CLDR source.
     *
     */
    public function provide(string $path): array;
}
