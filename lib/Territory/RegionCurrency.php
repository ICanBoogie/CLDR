<?php

namespace ICanBoogie\CLDR\Territory;

final class RegionCurrency
{
	/**
	 * @param array<string, array{ _from?: string, _to?: string, _tender?: string }> $data
	 */
	public static function from(array $data): self
	{
		$code = key($data);
		$properties = current($data);

		assert(is_string($code));
		assert(is_array($properties));

		return new self(
			code: $code,
			from: $properties['_from'] ?? null,
			to: $properties['_to'] ?? null,
			tender: ($properties['_tender'] ?? null) != 'false',
		);
	}

	private function __construct(
		public readonly string $code,
		public readonly ?string $from,
		public readonly ?string $to,
		public readonly bool $tender,
	) {
	}
}
