<?php

namespace ICanBoogie\CLDR\Provider;

use ICanBoogie\CLDR\Provider;

/**
 * A {@see Provider} that fails to provide any path.
 *
 * This provider restricts the usage of the repository to cached data.
 */
final class RestrictedProvider implements Provider
{
    public function provide(string $path): array
    {
        throw new ResourceNotFound("Only cached data is available, tried to read from: $path");
    }
}
