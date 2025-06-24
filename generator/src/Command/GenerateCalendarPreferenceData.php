<?php

namespace ICanBoogie\CLDR\Generator\Command;

use ICanBoogie\CLDR\Repository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarExporter\VarExporter;

use function ICanBoogie\CLDR\Generator\indent;

/**
 * Generates `CalendarPreferenceData.php` from
 * {@link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-core/supplemental/calendarPreferenceData.json}.
 */
#[AsCommand(self::GENERATED_FILE)]
final class GenerateCalendarPreferenceData extends Command
{
    private const GENERATED_FILE = 'src/Supplemental/CalendarPreferenceData.php';

    public function __construct(
        private readonly Repository $repository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $by_region = $this->repository->supplemental['calendarPreferenceData'];

        $contents = $this->render(
            by_region: indent(VarExporter::export($by_region), 2),
        );

        file_put_contents(self::GENERATED_FILE, $contents);

        return self::SUCCESS;
    }

    private function render(
        string $by_region,
    ): string {
        $class = __CLASS__;

        return <<<PHP
        <?php

        /**
         * CODE GENERATED; DO NOT EDIT.
         *
         * {@see \\$class}
         */

        namespace ICanBoogie\CLDR\Supplemental;

        /**
         * @codeCoverageIgnore
         */
        final class CalendarPreferenceData
        {
            /**
             * @link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-core/supplemental/calendarPreferenceData.json
             */
            public const BY_REGION =
        $by_region;

            /**
             * Returns the preference calendar for a region.
             */
            public static function preferred_calendar_for_region(string \$region): string
            {
                \$preference = self::BY_REGION[\$region] ?? self::BY_REGION['001'];

                return current(\$preference);
            }
        }

        PHP;
    }
}
