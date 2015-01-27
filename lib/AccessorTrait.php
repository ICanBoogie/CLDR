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

use ICanBoogie\PropertyNotDefined;
use ICanBoogie\PropertyNotReadable;

trait AccessorTrait
{
	public function __get($property)
	{
		return $this->__object_get($property);
	}

	private function __object_get($property)
	{
		$method = 'get_' . $property;

		if (method_exists($this, $method))
		{
			return $this->$method();
		}

		$method = 'lazy_get_' . $property;

		if (method_exists($this, $method))
		{
			return empty($this->$property) ? $this->$property = $this->$method() : $this->$property;
		}

		#
		# There is no method defined to get the requested property, the appropriate property
		# exception is raised.
		#

		$reflexion_class = new \ReflectionClass($this);

		try
		{
			$reflexion_property = $reflexion_class->getProperty($property);

			if (!$reflexion_property->isPublic())
			{
				throw new PropertyNotReadable([ $property, $this ]);
			}
		}
		catch (\ReflectionException $e)
		{
			# Reflection exceptions don't matter here, it's probably because the property
			# doesn't exist.
		}

		if (method_exists($this, 'set_' . $property))
		{
			throw new PropertyNotReadable([ $property, $this ]);
		}

		throw new PropertyNotDefined([ $property, $this ]);
	}
}
