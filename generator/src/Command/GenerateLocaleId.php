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
final class GenerateLocaleId extends Command
{
    private const GENERATED_FILE = 'src/Core/LocaleId.php';

    public function __construct(
        private readonly Repository $repository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parent_locales = $this->repository->supplemental['parentLocales'];
        $available_locales = $this->repository->available_locales;

        $contents = $this->render(
            parent_locales: indent(VarExporter::export($parent_locales), 2),
            available_locales: indent(VarExporter::export($available_locales), 2),
        );

        file_put_contents(self::GENERATED_FILE, $contents);

        return self::SUCCESS;
    }

    public function render(
        string $parent_locales,
        string $available_locales,
    ): string {
        $class = __CLASS__;

        return <<<PHP
        <?php

        /**
         * CODE GENERATED; DO NOT EDIT.
         *
         * {@see \\$class}
         */

        namespace ICanBoogie\CLDR\Core;

        final class LocaleId
        {
            /**
             * Whether a locale ID is available.
             *
             * @param string \$value
             *     A locale identifier; for example, fr-BE
             */
            public static function is_available(string \$value): bool
            {
                if (isset(self::PARENT_LOCALES[\$value])) {
                    return true;
                }

                return in_array(\$value, self::AVAILABLE_LOCALES);
            }

            /**
             * @param string \$value
             *     A locale identifier; for example, fr-BE
             *
             * @throws LocaleNotAvailable
             */
            public static function assert_is_available(string \$value): void
            {
                self::is_available(\$value)
                    or throw new LocaleNotAvailable(\$value);
            }

            /**
             * Returns a {@see LocaleId} of a value.
             *
             * Note: If the locale has a parent locale, that locale is used instead.
             *
             * @param string \$value
             *     A locale identifier; for example, fr-BE
             *
             * @throws LocaleNotAvailable
             */
            public static function of(string \$value): self
            {
                static \$instances;

                self::assert_is_available(\$value);

                if (isset(self::PARENT_LOCALES[\$value])) {
                    \$value = self::PARENT_LOCALES[\$value];
                }

                return \$instances[\$value] ??= new self(\$value);
            }

            /**
             * @param string \$value
             *     A locale identifier; for example, fr-BE.
             */
            private function __construct(
                public readonly string \$value,
            ) {
            }

            /**
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-core/supplemental/parentLocales.json
             */
            public const PARENT_LOCALES =
        $parent_locales;

            /**
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-core/availableLocales.json
             */
            public const AVAILABLE_LOCALES =
        $available_locales;
        }

        PHP;
    }
}
