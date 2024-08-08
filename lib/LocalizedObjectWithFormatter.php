<?php

namespace ICanBoogie\CLDR;

/**
 * A localized object with a formatter.
 *
 * @template TTarget of object
 * @template TFormatter of Formatter
 *
 * @extends LocalizedObject<TTarget>
 *
 * @property-read TFormatter $formatter The formatter used to format the target object.
 *
 * @method string format() Formats the instance's target. Although the method is not
 * defined by the class, it should be implemented by subclasses.
 */
abstract class LocalizedObjectWithFormatter extends LocalizedObject
{
	/**
	 * @var TFormatter|null
	 */
	private $formatter;

	/**
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get($property)
	{
		if ($property === 'formatter') {
			return $this->formatter ??= $this->lazy_get_formatter();
		}

		return parent::__get($property);
	}

	/**
	 * Returns the formatter used to format the target object.
	 *
	 * @return TFormatter
	 */
	abstract protected function lazy_get_formatter(): Formatter;
}
