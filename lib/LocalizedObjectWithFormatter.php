<?php

namespace ICanBoogie\CLDR;

/**
 * A localized object with a formatter.
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read mixed $formatter The formatter used ot format the target object.
 *
 * @method string format() format() Formats the instance's target. Although the method is not
 * defined by the class, it should be implemented by sub-classes.
 */
abstract class LocalizedObjectWithFormatter extends LocalizedObject
{
	/**
	 * @var mixed
	 */
	private $formatter;

	public function __get($property)
	{
		if ($property == 'formatter')
		{
			if (!$this->formatter)
			{
				$this->formatter = $this->lazy_get_formatter();
			}

			return $this->formatter;
		}

		return parent::__get($property);
	}

	/**
	 * Returns the formatter to use to format the target object.
	 *
	 * @return mixed
	 */
	abstract protected function lazy_get_formatter();
}
