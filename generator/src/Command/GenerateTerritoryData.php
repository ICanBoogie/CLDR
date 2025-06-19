<?php

namespace ICanBoogie\CLDR\Generator\Command;

use ICanBoogie\CLDR\Repository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarExporter\VarExporter;
use function ICanBoogie\CLDR\Generator\indent;

#[AsCommand(self::GENERATED_FILE)]
final class GenerateTerritoryData extends Command
{
    private const GENERATED_FILE = 'src/Supplemental/Territory/TerritoryData.php';

    public function __construct(
        private readonly Repository $repository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var string[] $codes
         *
         * @link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-localenames-full/main/en-001/territories.json
         */
        $codes = array_keys($this->repository->locale_for('en-001')['territories']);
        $codes = array_values(array_filter($codes, fn($code) => !str_contains($code, '-alt')));
        $codes = array_map(fn ($v) => (string) $v, $codes);;

        $contents = $this->render(
            codes: indent(VarExporter::export($codes), 2),
        );

        file_put_contents(self::GENERATED_FILE, $contents);

        return self::SUCCESS;
    }

    private function render(
        string $codes,
    ): string {
        $class = __CLASS__;

        return <<<PHP
        <?php

        /**
         * CODE GENERATED; DO NOT EDIT.
         *
         * {@see \\$class}
         */

        namespace ICanBoogie\CLDR\Supplemental\Territory;

        /**
         * @internal
         * @codeCoverageIgnore
         */
        final class TerritoryData
        {
            /**
             * @link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-localenames-full/main/en-001/territories.json
             */
            public const CODES =
        $codes;

            private function __construct()
            {
            }
        }

        PHP;
    }
}
