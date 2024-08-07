<?php

namespace ICanBoogie\CLDR\Provider;

use ICanBoogie\CLDR\Provider;
use ICanBoogie\CLDR\ResourceNotFound;

/**
 * A {@see Provider} that fails to provide any path.
 *
 * This provider is useful when you want to restrict the usage of the repository to warmed-up data.
 */
final class FailingProvider implements Provider
{
	public function provide(string $path): array
	{
		throw new ResourceNotFound("Only warmed-up data is available, tried to read from: $path");
	}
}
