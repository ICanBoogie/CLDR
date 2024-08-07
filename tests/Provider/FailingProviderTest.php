<?php

namespace Test\ICanBoogie\CLDR\Provider;

use ICanBoogie\CLDR\Provider\FailingProvider;
use LogicException;
use PHPUnit\Framework\TestCase;

/**
 * @group static
 */
class FailingProviderTest extends TestCase
{
	public function test_provide(): void
	{
		$sut = new FailingProvider();

		$this->expectException(LogicException::class);
		$this->expectExceptionMessageMatches("/Only warmed-up data is available/");

		$sut->provide("foo");
	}
}
