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
final class GenerateCurrencyData extends Command
{
    private const GENERATED_FILE = 'src/Numbers/CurrencyData.php';

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
         * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-numbers-full/main/en-001/currencies.json
         */
        $codes = array_keys($this->repository->locale_for('en-001')['currencies']);

        /**
         * @var array<string, array{
         *     _rounding: string,
         *     _digits: string,
         *     _cashRounding?: string,
         *     _cashDigits?: string
         * }> $fractions
         *
         * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-core/supplemental/currencyData.json
         */
        $fractions = $this->repository->supplemental['currencyData']['fractions'];

        $contents = $this->render(
            codes: indent(VarExporter::export($codes), 2),
            fractions: indent(VarExporter::export($fractions), 2),
        );

        file_put_contents(self::GENERATED_FILE, $contents);

        return self::SUCCESS;
    }

    private function render(
        string $codes,
        string $fractions,
    ): string {
        $class = __CLASS__;

        return <<<PHP
        <?php

        /**
         * CODE GENERATED; DO NOT EDIT.
         *
         * {@see \\$class}
         */

        namespace ICanBoogie\CLDR\Numbers;

        /**
         * @internal
         * @codeCoverageIgnore
         */
        final class CurrencyData
        {
            /**
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-numbers-modern/main/en-001/currencies.json
             */
            public const CODES =
        $codes;

            /**
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-core/supplemental/currencyData.json
             */
            public const FRACTIONS =
        $fractions;

            public const FRACTIONS_FALLBACK = 'DEFAULT';

            private function __construct()
            {
            }
        }

        PHP;
    }
}
