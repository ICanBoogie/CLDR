<?php

namespace Test\ICanBoogie\CLDR\Provider;

use ICanBoogie\CLDR\Provider\ResourceNotFound;
use ICanBoogie\CLDR\Provider\RestrictedProvider;
use PHPUnit\Framework\TestCase;

/**
 * @group static
 */
class RestrictedProviderTest extends TestCase
{
    public function test_provide(): void
    {
        $sut = new RestrictedProvider();

        $this->expectException(ResourceNotFound::class);
        $this->expectExceptionMessageMatches("/Only cached data is available/");

        $sut->provide("foo");
    }
}
