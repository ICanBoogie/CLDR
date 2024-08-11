<?php

namespace Test\ICanBoogie\CLDR\Provider;

use ICanBoogie\CLDR\Provider\ResourceNotFound;
use ICanBoogie\CLDR\Provider\WebProvider;
use PHPUnit\Framework\TestCase;

final class WebProviderTest extends TestCase
{
    public function test_provide_ok(): void
    {
        $provider = new WebProvider();
        $data = $provider->provide('misc/fr/characters');

        $this->assertIsArray($data);
        $this->assertArrayHasKey('main', $data);
    }

    public function test_provide_failure(): void
    {
        $this->expectException(ResourceNotFound::class);
        $provider = new WebProvider();
        $path = 'undefined_locale/characters';

        $this->expectException(ResourceNotFound::class);
        $provider->provide($path);
    }
}
