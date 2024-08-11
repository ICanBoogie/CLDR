<?php

namespace Test\ICanBoogie\CLDR\Units;

use ICanBoogie\CLDR\Units\Sequence;
use ICanBoogie\CLDR\Units\UnitLength;
use ICanBoogie\CLDR\Units\Units;
use PHPUnit\Framework\TestCase;

final class SequenceTest extends TestCase
{
    public function test_format(): void
    {
        $unit = "digital-megabyte";
        $method = strtr($unit, '-', '_');
        $number = mt_rand(100, 200);
        $expected = uniqid();
        $length = UnitLength::NARROW;

        $units = $this->getMockBuilder(Units::class)
            ->onlyMethods([ 'format_sequence' ])
            ->disableOriginalConstructor()
            ->getMock();
        $units
            ->expects($this->once())
            ->method('format_sequence')
            ->with([ $unit => $number ], $length)
            ->willReturn($expected);

        $unit = new Sequence($units);
        $this->assertSame($expected, $unit->$method($number)->format($length));
    }

    public function test_to_string(): void
    {
        $unit = "digital-megabyte";
        $method = strtr($unit, '-', '_');
        $number = mt_rand(100, 200);
        $expected = uniqid();
        $length = Units::DEFAULT_LENGTH;

        $units = $this->getMockBuilder(Units::class)
            ->onlyMethods([ 'format_sequence' ])
            ->disableOriginalConstructor()
            ->getMock();
        $units
            ->expects($this->once())
            ->method('format_sequence')
            ->with([ $unit => $number ], $length)
            ->willReturn($expected);

        $unit = new Sequence($units);
        $this->assertSame($expected, (string)$unit->$method($number));
    }
}
