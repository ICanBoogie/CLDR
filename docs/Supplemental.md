# Supplemental

This is information that is important for internationalization and proper use of CLDR, but is not
contained in the locale hierarchy. It is not localizable, nor is it overridden by locale data.

## Supplemental Currency Data

Use `fraction_for()` to get the fraction information for a currency:

```php
<?php

/* @var $cldr \ICanBoogie\CLDR\Repository */

$euro_fraction = $cldr->supplemental->currency_data->fraction_for('EUR');

echo $euro_fraction->digits;        // 2
echo $euro_fraction->rounding;      // 0
echo $euro_fraction->cash_digits;   // 2
echo $euro_fraction->cash_rounding; // 0
```

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#Supplemental_Currency_Data)
