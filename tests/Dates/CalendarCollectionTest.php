<?php

namespace Test\ICanBoogie\CLDR\Dates;

use BadMethodCallException;
use ICanBoogie\CLDR\Dates\Calendar;
use ICanBoogie\CLDR\Dates\CalendarCollection;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Test\ICanBoogie\CLDR\locale_for;

final class CalendarCollectionTest extends TestCase
{
    private static CalendarCollection $collection;

    public static function setupBeforeClass(): void
    {
        self::$collection = locale_for('fr')->calendars;
    }

    public function test_offsetExists(): void
    {
        $this->expectException(BadMethodCallException::class);
        self::$collection->offsetExists('gregorian');
    }

    public function test_offsetSet(): void
    {
        $this->expectException(LogicException::class);
        self::$collection['gregorian'] = null;
    }

    public function test_offsetUnset(): void
    {
        $this->expectException(LogicException::class);
        unset(self::$collection['gregorian']);
    }

    #[DataProvider('provide_test_get')]
    public function test_get(string $calendar_id): void
    {
        $calendar = self::$collection[$calendar_id];
        $this->assertInstanceOf(Calendar::class, $calendar);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_get(): array
    {
        return [

            [ 'buddhist' ],
            [ 'chinese' ],
            [ 'coptic' ],
            [ 'dangi' ],
            [ 'ethiopic' ],
            [ 'generic' ],
            [ 'gregorian' ],
            [ 'hebrew' ],
            [ 'indian' ],
            [ 'islamic' ],
            [ 'japanese' ],
            [ 'persian' ],
            [ 'roc' ]

        ];
    }
}
