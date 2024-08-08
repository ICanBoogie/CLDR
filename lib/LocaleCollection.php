<?php

namespace ICanBoogie\CLDR;

use ICanBoogie\Accessor\AccessorTrait;

/**
 * Representation of a locale collection.
 *
 * @extends AbstractCollection<Locale>
 */
class LocaleCollection extends AbstractCollection
{
	use AccessorTrait;

	public function __construct(
		public readonly Repository $repository
	) {
		parent::__construct(function (string $code): Locale {
			LocaleId::assert_id_available($code);

			return new Locale($this->repository, LocaleId::from($code));
		});
	}

	public function locale_for(string|LocaleId $locale_id): Locale
	{
		if ($locale_id instanceof LocaleId) {
			$locale_id = $locale_id->value;
		}

		/** @var Locale */
		return $this->offsetGet($locale_id);
	}
}
