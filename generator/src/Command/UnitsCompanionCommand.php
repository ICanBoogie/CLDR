<?php

namespace ICanBoogie\CLDR\Generator\Command;

use ICanBoogie\CLDR\Repository;
use ICanBoogie\CLDR\Units\NumberWithUnit;
use ICanBoogie\CLDR\Units\Unit;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('units-companion', "Generates Units/UnitsCompanion.php")]
final class UnitsCompanionCommand extends Command
{
    private const TEMPLATE = <<<PHP
    <?php

    /** DO NOT EDIT - THE FILE HAS BEEN GENERATED WITH units-companion */

    namespace ICanBoogie\CLDR\Units;

    /**
     * @internal
     *
    #PROPERTIES#
     *
     */
    trait UnitsCompanion
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
        $properties = [];
        $methods = [];

        foreach ($units as $name => $unit) {
            if (empty($unit['unitPattern-count-one'])) {
                continue;
            }

            $normalized = strtr($name, [ '-' => '_' ]);

            $properties[] = <<<TXT
             * @property-read Unit \$$normalized
            TXT;

            $methods[] = <<<PHP
                /**
                 * @param float|int|numeric-string \$number
                 */
                public function $normalized(float|int|string \$number): NumberWithUnit
                {
                    return new NumberWithUnit(\$number, "$name", \$this);
                }

            PHP;
        }

        echo strtr(self::TEMPLATE, [
            '#PROPERTIES#' => implode("\n", $properties),
            '#METHODS#' => implode("\n", $methods),
        ]);

        return self::SUCCESS;
    }
}
