<?php

namespace ICanBoogie\CLDR\Generator\Command;

use ICanBoogie\CLDR\Repository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarExporter\VarExporter;
use function ICanBoogie\CLDR\Generator\indent;

#[AsCommand('lib/Currency.php')]
final class GenerateCurrency extends Command
{
	private const GENERATED_FILE = 'lib/Currency.php';

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
		 * @phpstan-ignore-next-line
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
		 * @phpstan-ignore-next-line
		 */
		$fractions = $this->repository->supplemental['currencyData']['fractions'];

		$contents = $this->render(
			codes: indent(VarExporter::export($codes), 1),
			fractions: indent(VarExporter::export($fractions), 1),
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

		namespace ICanBoogie\CLDR;

		use ICanBoogie\CLDR\Supplemental\Fraction;

		/**
		 * Representation of a currency.
		 *
		 * @link https://www.unicode.org/reports/tr35/tr35-72/tr35-numbers.html#Currencies
		 *
		 * @implements Localizable<Currency, LocalizedCurrency>
		 */
		final class Currency implements Localizable
		{
			/**
			 * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-numbers-modern/main/en-001/currencies.json
			 */
			public const CODES =
		$codes;

			/**
			 * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-core/supplemental/currencyData.json
			 */
			private const FRACTIONS =
		$fractions;

			private const FRACTIONS_FALLBACK = 'DEFAULT';

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
			 * @throws CurrencyNotDefined
			 */
			public static function assert_is_defined(string \$code): void
			{
				self::is_defined(\$code)
					or throw new CurrencyNotDefined(\$code);
			}

			/**
			 * Returns a {@see CurrencyCode} of the specified code.
			 *
			 * @param string \$code
			 *     A currency code; for example, EUR.
			 *
			 * @throws CurrencyNotDefined
			 */
			public static function of(string \$code): self
			{
				static \$instances;

				self::assert_is_defined(\$code);

				return \$instances[\$code] ??= new self(\$code, self::fraction_for(\$code));
			}

			/**
			 * Returns the {@see Fraction} for the specified currency code.
			 *
			 * @param string \$code
			 * *     A currency code; for example, EUR.
			 */
			private static function fraction_for(string \$code): Fraction
			{
				static \$default_fraction;

				\$data = self::FRACTIONS[\$code] ?? null;

				if (!\$data)
				{
					return \$default_fraction ??= self::fraction_for(self::FRACTIONS_FALLBACK);
				}

				return Fraction::from(\$data);
			}

			/**
			 * @param string \$code
			 *     A currency code; for example, EUR.
			*/
			private function __construct(
				public readonly string \$code,
				public readonly Fraction \$fraction,
			) {
			}

			/**
			 * Returns the {@see \$code} of the currency.
			 */
			public function __toString() : string
			{
				return \$this->code;
			}

			public function __serialize(): array
			{
				return [ 'code' => \$this->code ];
			}

			/**
			 * @param array{ code: string } \$data
			 */
			public function __unserialize(array \$data): void
			{
				\$this->code = \$data['code'];
				\$this->fraction = self::fraction_for(\$this->code);
			}

			/**
			 * Returns a localized currency.
			 */
			public function localized(Locale \$locale): LocalizedCurrency
			{
				return new LocalizedCurrency(\$this, \$locale);
			}
		}

		PHP;
	}
}
