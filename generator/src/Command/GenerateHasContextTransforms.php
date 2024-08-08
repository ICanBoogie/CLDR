<?php

namespace ICanBoogie\CLDR\Generator\Command;

use ICanBoogie\CLDR\Repository;
use ICanBoogie\CLDR\ResourceNotFound;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarExporter\VarExporter;

use function ICanBoogie\CLDR\Generator\indent;

#[AsCommand('lib/Locale/HasContextTransforms.php')]
final class GenerateHasContextTransforms extends Command
{
    private const GENERATED_FILE = 'lib/Locale/HasContextTransforms.php';

    public function __construct(
        private readonly Repository $repository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $w = $output->writeln(...);
        $has_by_locale = [];

        foreach ($this->repository->available_locales as $locale_id) {
            $has = true;

            try
            {
                $w("Checking $locale_id");
                $this->repository->fetch("misc/$locale_id/contextTransforms");
            }
            catch (ResourceNotFound)
            {
                $has = false;
            }

            $has_by_locale[$locale_id] = $has;
        }

        $contents = $this->render(
            has_by_locale: indent(VarExporter::export($has_by_locale), 1),
        );

        file_put_contents(self::GENERATED_FILE, $contents);

        return self::SUCCESS;
    }

    private function render(string $has_by_locale): string
    {
        return <<<PHP
        <?php

        /** CODE GENERATED; DO NOT EDIT. */

        namespace ICanBoogie\CLDR\Locale;

        use ICanBoogie\CLDR\LocaleId;

        final class HasContextTransforms
        {
            private const HAS_CONTEXT_TRANSFORMS =
        $has_by_locale;

            /**
             * Whether a locale has context transforms.
             */
            static public function for_locale(LocaleId \$locale_id): bool
            {
                return self::HAS_CONTEXT_TRANSFORMS[\$locale_id->value];
            }

            /**
             * @codeCoverageIgnore
             */
            private function __construct() {}
        }

        PHP;
    }
}
