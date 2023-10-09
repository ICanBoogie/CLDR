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

    use InvalidArgumentException;

    final class LocaleId
    {
        public static function is_available(string \$value): bool
        {
            if (isset(self::PARENT_MAP[\$value])) {
                return true;
            }

            return in_array(\$value, self::AVAILABLE_LOCALES);
        }

        /**
         * @var array<string, self>
         *     Where _key_ is a locale identifier.
         */
        private static array \$instances = [];

        /**
         * Returns a {@link LocaleId} of a value.
         *
         * @param string \$value
         *     A locale identifier.
         *
         * @return self
         *
         * @throws InvalidArgumentException if the locale is not available.
         */
        public static function from(string \$value): self
        {
            if (!self::is_available(\$value)) {
                throw new InvalidArgumentException("The locale '\$value' is not available");
            }

            if (isset(self::PARENT_MAP[\$value])) {
                \$value = self::PARENT_MAP[\$value];
            }

            return self::\$instances[\$value] ??= new self(\$value);
        }

        private function __construct(
            public readonly string \$value,
        ) {
        }

        /**
         * @see https://github.com/unicode-org/cldr-json/blob/41.0.0/cldr-json/cldr-core/supplemental/parentLocales.json
         */
        public const PARENT_MAP = [
    #PARENT_MAP#
        ];

        /**
         * @see https://github.com/unicode-org/cldr-json/blob/41.0.0/cldr-json/cldr-core/availableLocales.json
         */
        public const AVAILABLE_LOCALES = [
    #AVAILABLE_LOCALES_SET#
        ];
    }

    PHP;

    public function __construct(
        private readonly Repository $repository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $parent_locales = $this->repository->supplemental['parentLocales'];
        $available_locales = $this->repository->available_locales;

        $parent_map = [];

        foreach ($parent_locales as $pl => $l) {
            $parent_map[] = <<<PHP
                    "$pl" => "$l",
            PHP;
        }

        $available_locales_set = [];

        foreach ($available_locales as $locale) {
            $available_locales_set[] = <<<PHP
                    "$locale",
            PHP;
        }

        echo strtr(self::TEMPLATE, [
            '#PARENT_MAP#' => implode("\n", $parent_map),
            '#AVAILABLE_LOCALES_SET#' => implode("\n", $available_locales_set),
        ]);

        return self::SUCCESS;
    }
}
