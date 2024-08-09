<?php

namespace ICanBoogie\CLDR;

/**
 * An interface for classes that can provide CLDR data.
 */
interface Provider
{
    /**
     * The section path, following the pattern "<identity>/<section>".
     *
     * @return array<string, mixed>
     * @throws ResourceNotFound when the specified path does not exist on the CLDR source.
     *
     */
    public function provide(string $path): array;
}
