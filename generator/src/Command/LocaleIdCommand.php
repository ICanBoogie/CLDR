<?php

namespace ICanBoogie\CLDR\Generator\Command;

use ICanBoogie\CLDR\Repository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('locale-id', "Generates LocaleId.php")]
final class LocaleIdCommand extends Command
{
    private const TEMPLATE = <<<PHP
    <?php

    /** DO NOT EDIT - THE FILE HAS BEEN GENERATED WITH locale-id */

    namespace ICanBoogie\CLDR;

    enum LocaleId: string
    {
    #CASES#
    }

    PHP;

    public function __construct(
        private readonly Repository $repository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $available_locales = $this->repository->available_locales;
        $cases = [];

        foreach ($available_locales as $locale) {
            $case = strtr($locale, [ '-' => '_' ]);

            $cases[] = <<<PHP
                case $case = "$locale";
            PHP;
        }

        $cases = implode("\n", $cases);

        echo strtr(self::TEMPLATE, [ '#CASES#' => $cases ]);

        return self::SUCCESS;
    }
}
