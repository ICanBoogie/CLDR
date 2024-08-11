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
final class GenerateLocaleData extends Command
{
    private const GENERATED_FILE = 'src/Core/LocaleData.php';

    public function __construct(
        private readonly Repository $repository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parent_locales = $this->repository->supplemental['parentLocales'];
        $available_locales = $this->repository->fetch('core/availableLocales', 'availableLocales/full');

        $contents = $this->render(
            available_locales: indent(VarExporter::export($available_locales), 2),
            parent_locales: indent(VarExporter::export($parent_locales), 2),
        );

        file_put_contents(self::GENERATED_FILE, $contents);

        return self::SUCCESS;
    }

    public function render(
        string $available_locales,
        string $parent_locales,
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

        /**
         * @internal
         * @codeCoverageIgnore
         */
        final class LocaleData
        {
            /**
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-core/availableLocales.json
             */
            public const AVAILABLE_LOCALES =
        $available_locales;

            /**
             * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-core/supplemental/parentLocales.json
             */
            public const PARENT_LOCALES =
        $parent_locales;

            private function __construct()
            {
            }
        }

        PHP;
    }
}
