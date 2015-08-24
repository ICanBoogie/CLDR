<?php

namespace ICanBoogie\CLDR\CodePropertyTraitTest;

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\CodePropertyTrait;

class A
{
	use AccessorTrait;
	use CodePropertyTrait;

	public function __construct($code)
	{
		$this->code = $code;
	}
}
