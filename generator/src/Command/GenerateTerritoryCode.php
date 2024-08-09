<?php

namespace ICanBoogie\CLDR\Generator\Command;

use ICanBoogie\CLDR\Repository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarExporter\VarExporter;
use function ICanBoogie\CLDR\Generator\indent;

#[AsCommand('lib/TerritoryCode.php')]
final class GenerateTerritoryCode extends Command
{
    private const GENERATED_FILE = 'lib/TerritoryCode.php';

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
         * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-localenames-full/main/en-001/territories.json
         * @phpstan-ignore-next-line
         */
        $codes = array_keys($this->repository->locale_for('en-001')['territories']);
        $codes = array_values(array_filter($codes, fn($code) => !str_contains($code, '-alt')));

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

        namespace ICanBoogie\CLDR;

        /**
         * A territory code.
         */
        final class TerritoryCode
        {
            /**
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-localenames-full/main/en-001/territories.json
             */
            public const CODES =
        $codes;

            /**
             * Whether a currency code is defined.
             *
             * @param string \$code
             *     A currency code; for example, EUR.
             */
            public static function is_defined(string \$code): bool
            {
                return in_array(\$code, self::CODES);
            }

            /**
             * @param string \$code
             *     A currency code; for example, EUR.
             *
             * @throws TerritoryNotDefined
             */
            public static function assert_is_defined(string \$code): void
            {
                self::is_defined(\$code)
                    or throw new TerritoryNotDefined(\$code);
            }

            /**
             * Returns a {@see CurrencyCode} of the specified code.
             *
             * @param string \$code
             *     A currency code; for example, EUR.
             *
             * @throws TerritoryNotDefined
             */
            public static function of(string \$code): self
            {
                static \$instances;

                self::assert_is_defined(\$code);

                return \$instances[\$code] ??= new self(\$code);
            }

            /**
             * @param string \$value
             *     A territory value; for example, CA.
            */
            private function __construct(
                public readonly string \$value,
            ) {
            }

            /**
             * Returns the {@see \$value} of the currency.
             */
            public function __toString(): string
            {
                return \$this->value;
            }

            public function __serialize(): array
            {
                return [ 'value' => \$this->value ];
            }

            /**
             * @param array{ value: string } \$data
             */
            public function __unserialize(array \$data): void
            {
                \$this->value = \$data['value'];
            }
        }

        PHP;
    }
}
