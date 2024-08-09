<?php

namespace ICanBoogie\CLDR\Generator\Command;

use ICanBoogie\CLDR\Repository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('lib/Units/SequenceCompanion.php')]
final class GenerateSequenceCompanion extends Command
{
    private const GENERATED_FILE = 'lib/Units/SequenceCompanion.php';

    public function __construct(
        private readonly Repository $repository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // @phpstan-ignore-next-line
        $units = $this->repository->locale_for('en-001')['units']['long'];
        $methods = [];

        foreach ($units as $name => $unit) {
            if (empty($unit['unitPattern-count-one'])) {
                continue;
            }

            $normalized = strtr($name, [ '-' => '_' ]);

            $methods[] = <<<PHP
                /**
                 * @param float|int|numeric-string \$number
                 *
                 * @return \$this
                 */
                public function $normalized(float|int|string \$number): self
                {
                    \$this->sequence["$name"] = \$number;

                    return \$this;
                }
            PHP;
        }

        $contents = $this->render(
            methods: implode("\n\n", $methods),
        );

        file_put_contents(self::GENERATED_FILE, $contents);

        return self::SUCCESS;
    }

    private function render(
        string $methods,
    ): string {
        $class = __CLASS__;

        return <<<PHP
        <?php

        /**
         * CODE GENERATED; DO NOT EDIT.
         *
         * {@see \\$class}
         */

        namespace ICanBoogie\CLDR\Units;

        /**
         * @internal
         * @codeCoverageIgnore
         */
        trait SequenceCompanion
        {
        $methods
        }

        PHP;
    }
}
