<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR;

/**
 * A localized object with a formatter.
 *
 * @property-read Formatter $formatter The formatter used to format the target object.
 *
 * @method string format() Formats the instance's target. Although the method is not
 * defined by the class, it should be implemented by sub-classes.
 */
abstract class LocalizedObjectWithFormatter extends LocalizedObject
{
	/**
	 * @var Formatter
	 */
	private $formatter;

	/**
	 * @inheritdoc
	 */
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
	 * @return Formatter
	 */
	abstract protected function lazy_get_formatter();
}
