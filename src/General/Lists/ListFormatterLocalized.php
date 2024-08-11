<?php

namespace ICanBoogie\CLDR\General\Lists;

use ICanBoogie\CLDR\Core\Formatter;
use ICanBoogie\CLDR\Core\LocalizedObject;

/**
 * Formats variable-length lists of things.
 *
 * @extends LocalizedObject<ListFormatter>
 *
 * @link https://www.unicode.org/reports/tr35/tr35-72/tr35-general.html#ListPatterns
 */
class ListFormatterLocalized extends LocalizedObject implements Formatter
{
    /**
     * Formats variable-length lists of scalars.
     *
     * @param scalar[] $list
     */
    public function format(array $list, ListType $type = ListType::STANDARD): string
    {
        $list_pattern = ListPattern::from($this->locale['listPatterns']["listPattern-type-$type->value"]);

        return $this->target->format($list, $list_pattern);
    }
}
