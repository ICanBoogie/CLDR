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
	 * @throws ResourceNotFound when the specified path does not exist on the CLDR source.
	 *
	 * @return array<string, mixed>
	 */
	public function provide(string $path): array;
}
