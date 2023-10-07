<?php

namespace ICanBoogie\CLDR\Generator\Command;

use ICanBoogie\CLDR\Repository;
use ICanBoogie\CLDR\Units\NumberWithUnit;
use ICanBoogie\CLDR\Units\Unit;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('sequence-companion', "Generates Units/SequenceCompanion.php")]
final class SequenceCompanionCommand extends Command
{
    private const TEMPLATE = <<<PHP
    <?php

    /** DO NOT EDIT - THE FILE HAS BEEN GENERATED WITH sequence-companion */

    namespace ICanBoogie\CLDR\Units;

    /**
     * @internal
     */
    trait SequenceCompanion
    {
    #METHODS#
    }

    PHP;

    public function __construct(
        private readonly Repository $repository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $units = $this->repository->locales['en-001']['units']['long'];
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

        echo strtr(self::TEMPLATE, [
            '#METHODS#' => implode("\n", $methods),
        ]);

        return self::SUCCESS;
    }
}
