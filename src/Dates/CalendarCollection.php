<?php

namespace ICanBoogie\CLDR\Dates;

use ICanBoogie\CLDR\AbstractCollection;
use ICanBoogie\CLDR\Core\Locale;

use function is_array;

/**
 * Representation of a calendar collection.
 *
 * <pre>
 * <?php
 *
 * $calendar_collection = $repository->locale_for('fr')->calendars;
 * $gregorian_calendar = $calendar_collection['gregorian'];
 * </pre>
 *
 * @extends AbstractCollection<Calendar>
 */
final class CalendarCollection extends AbstractCollection
{
    public function __construct(
        public readonly Locale $locale
    ) {
        parent::__construct($this->new(...));
    }

    private function new(string $id): Calendar
    {
        $data = $this->locale["ca-$id"];

        assert(is_array($data));

        return new Calendar($this->locale, $data);
    }
}
