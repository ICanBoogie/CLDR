<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR;

class DateTimeFormatterTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var DateTimeFormatter[]
	 */
	static private $formatters = [];

	static public function setupBeforeClass()
	{
		$repository = get_repository();

		self::$formatters = [

			'en' => new DateTimeFormatter($repository->locales['en']->calendar),
			'fr' => new DateTimeFormatter($repository->locales['fr']->calendar)

		];
	}

	public function test_get_calendar()
	{
		$this->assertInstanceOf(Calendar::class, self::$formatters['en']->calendar);
	}

	/**
	 * @dataProvider provide_test_format
	 *
	 * @param string $locale_id
	 * @param string $datetime
	 * @param string $format
	 * @param string $expected
	 */
	public function test_format($locale_id, $datetime, $format, $expected)
	{
		$formatter = self::$formatters[$locale_id];

		$this->assertSame($expected, $formatter($datetime, $format));
	}

	public function provide_test_format()
	{
		return [

			# test: era

			[ 'fr', '2016-06-06 13:30:40', 'G', 'ap. J.-C.' ],
			[ 'fr', '2016-06-06 13:30:40', 'GG', 'ap. J.-C.' ],
			[ 'fr', '2016-06-06 13:30:40', 'GGG', 'ap. J.-C.' ],
			[ 'fr', '2016-06-06 13:30:40', 'GGGG', 'après Jésus-Christ' ],
			[ 'fr', '2016-06-06 13:30:40', 'GGGGG', 'ap. J.-C.' ],
			[ 'fr', '2016-06-06 13:30:40', 'GGGGGG', '' ],

			[ 'fr', '-0605-06-06 13:30:40', 'G', 'av. J.-C.' ],
			[ 'fr', '-0605-06-06 13:30:40', 'GG', 'av. J.-C.' ],
			[ 'fr', '-0605-06-06 13:30:40', 'GGG', 'av. J.-C.' ],
			[ 'fr', '-0605-06-06 13:30:40', 'GGGG', 'avant Jésus-Christ' ],
			[ 'fr', '-0605-06-06 13:30:40', 'GGGGG', 'av. J.-C.' ],
			[ 'fr', '-0605-06-06 13:30:40', 'GGGGGG', '' ],

			# test: format year one figure

			[ 'en', '0005-01-01', 'y', '5' ],
			[ 'en', '0005-01-01', 'yy', '05' ],
			[ 'en', '0005-01-01', 'yyy', '005' ],
			[ 'en', '0005-01-01', 'yyyy', '0005' ],
			[ 'en', '0005-01-01', 'yyyyy', '00005' ],

			# test: format year two figures

			[ 'en', '0045-01-01', 'y', '45' ],
			[ 'en', '0045-01-01', 'yy', '45' ],
			[ 'en', '0045-01-01', 'yyy', '045' ],
			[ 'en', '0045-01-01', 'yyyy', '0045' ],
			[ 'en', '0045-01-01', 'yyyyy', '00045' ],

			# test: format year three figures

			[ 'en', '0345-01-01', 'y', '345' ],
			[ 'en', '0345-01-01', 'yy', '45' ],
			[ 'en', '0345-01-01', 'yyy', '345' ],
			[ 'en', '0345-01-01', 'yyyy', '0345' ],
			[ 'en', '0345-01-01', 'yyyyy', '00345' ],

			# test: format year four figures

			[ 'en', '2345-01-01', 'y', '2345' ],
			[ 'en', '2345-01-01', 'yy', '45' ],
			[ 'en', '2345-01-01', 'yyy', '2345' ],
			[ 'en', '2345-01-01', 'yyyy', '2345' ],
			[ 'en', '2345-01-01', 'yyyyy', '02345' ],

			/* failing because DateTime converts the date 12345 to 2005
			 * FIXME
			# test: format year five figures

			array('en', '12345-01-01', 'y', '12345'),
			array('en', '12345-01-01', 'yy', '45'),
			array('en', '12345-01-01', 'yyy', '12345'),
			array('en', '12345-01-01', 'yyyy', '12345'),
			array('en', '12345-01-01', 'yyyyy', '112345'),
			*/

			# test: format month

			[ 'en', '2012-02-13', 'M', '2' ],
			[ 'en', '2012-02-13', 'MM', '02' ],
			[ 'en', '2012-02-13', 'MMM', 'Feb' ],
			[ 'en', '2012-02-13', 'MMMM', 'February' ],
			[ 'en', '2012-02-13', 'MMMMM', 'F' ],
			[ 'en', '2012-02-13', 'MMMMMM', '' ],

			# test: format month in french

			[ 'fr', '2012-02-13', 'M', '2' ],
			[ 'fr', '2012-02-13', 'MM', '02' ],
			[ 'fr', '2012-02-13', 'MMM', 'févr.' ],
			[ 'fr', '2012-02-13', 'MMMM', 'février' ],
			[ 'fr', '2012-02-13', 'MMMMM', 'F' ],
			[ 'fr', '2012-02-13', 'MMMMMM', '' ],

			# test: format stand-alone month

			[ 'en', '2012-02-13', 'L', '2' ],
			[ 'en', '2012-02-13', 'LL', '02' ],
			[ 'en', '2012-02-13', 'LLL', 'Feb' ],
			[ 'en', '2012-02-13', 'LLLL', 'February' ],
			[ 'en', '2012-02-13', 'LLLLL', 'F' ],
			[ 'en', '2012-02-13', 'LLLLLL', '' ],

			# test: format stand-alone month in french

			[ 'fr', '2012-02-13', 'L', '2' ],
			[ 'fr', '2012-02-13', 'LL', '02' ],
			[ 'fr', '2012-02-13', 'LLL', 'Févr.' ],
			[ 'fr', '2012-02-13', 'LLLL', 'Février' ],
			[ 'fr', '2012-02-13', 'LLLLL', 'F' ],
			[ 'fr', '2012-02-13', 'LLLLLL', '' ],

			# test: format week of year

			[ 'en', '2012-01-01', 'w', '52' ],
			[ 'en', '2012-01-02', 'w', '1' ],
			[ 'en', '2012-01-02', 'ww', '01' ],
			[ 'en', '2012-01-02', 'www', '' ],
			[ 'en', '2012-12-30', 'w', '52' ],
			[ 'en', '2012-12-30', 'ww', '52' ],
			[ 'en', '2012-12-30', 'www', '' ],

			# test: format week of month

			[ 'en', '2012-01-01', 'W', '1' ],
			[ 'en', '2012-01-02', 'W', '1' ],
			[ 'en', '2012-01-09', 'W', '2' ],
			[ 'en', '2012-01-16', 'W', '3' ],
			[ 'en', '2012-01-23', 'W', '4' ],
			[ 'en', '2012-01-30', 'W', '5' ],
			[ 'en', '2012-01-30', 'WW', '' ],
			[ 'en', '2012-01-30', 'WWW', '' ],
			[ 'en', '2012-01-30', 'WWWW', '' ],
			[ 'en', '2012-01-30', 'WWWWW', '' ],

			# test: format day of the month

			[ 'en', '2012-01-01', 'd', '1' ],
			[ 'en', '2012-01-01', 'dd', '01' ],
			[ 'en', '2012-01-13', 'd', '13' ],
			[ 'en', '2012-01-13', 'dd', '13' ],
			[ 'en', '2012-01-13', 'ddd', '' ],
			[ 'en', '2012-01-13', 'dddd', '' ],
			[ 'en', '2012-01-13', 'ddddd', '' ],

			# test: format day of the year

			[ 'en', '2012-01-01', 'D', '1' ],
			[ 'en', '2012-01-01', 'DD', '01' ],
			[ 'en', '2012-01-01', 'DDD', '001' ],
			[ 'en', '2012-01-01', 'DDDD', '' ],
			[ 'en', '2012-01-01', 'DDDDD', '' ],
			[ 'en', '2012-01-13', 'DD', '13' ],
			[ 'en', '2012-01-13', 'DDD', '013' ],
			[ 'en', '2012-06-13', 'DD', '165' ],
			[ 'en', '2012-06-13', 'DDD', '165' ],
			[ 'en', '2012-06-13', 'DDDD', '' ],
			[ 'en', '2012-06-13', 'DDDDD', '' ],

			# test: format day of week
			/* FIXME
			array('en', '2012-06-01', 'F', '1'),
			array('en', '2012-06-03', 'F', '3'),
			array('en', '2012-06-05', 'F', '5'),
			*/

			# test: format weekday

			[ 'en', '2012-06-01', 'E', 'Fri' ],
			[ 'en', '2012-06-01', 'EE', 'Fri' ],
			[ 'en', '2012-06-01', 'EEE', 'Fri' ],
			[ 'en', '2012-06-01', 'EEEE', 'Friday' ],
			[ 'en', '2012-06-01', 'EEEEE', 'F' ],
			[ 'en', '2012-06-01', 'EEEEEE', 'Fr' ],
			[ 'en', '2012-06-01', 'EEEEEEE', '' ],

			# test: format weekday in french

			[ 'fr', '2012-06-01', 'E', 'ven.' ],
			[ 'fr', '2012-06-01', 'EE', 'ven.' ],
			[ 'fr', '2012-06-01', 'EEE', 'ven.' ],
			[ 'fr', '2012-06-01', 'EEEE', 'vendredi' ],
			[ 'fr', '2012-06-01', 'EEEEE', 'V' ],
			[ 'fr', '2012-06-01', 'EEEEEE', 've' ],
			[ 'fr', '2012-06-01', 'EEEEEEE', '' ],

			# test: format local weekday

			[ 'en', '2012-06-01', 'e', '5' ],
			[ 'en', '2012-06-01', 'ee', '5' ],
			[ 'en', '2012-06-01', 'eee', 'Fri' ],
			[ 'en', '2012-06-01', 'eeee', 'Friday' ],
			[ 'en', '2012-06-01', 'eeeee', 'F' ],
			[ 'en', '2012-06-01', 'eeeeee', 'Fr' ],
			[ 'en', '2012-06-01', 'eeeeeee', '' ],

			# test: format local weekday in french

			[ 'fr', '2012-06-01', 'e', '5' ],
			[ 'fr', '2012-06-01', 'ee', '5' ],
			[ 'fr', '2012-06-01', 'eee', 'ven.' ],
			[ 'fr', '2012-06-01', 'eeee', 'vendredi' ],
			[ 'fr', '2012-06-01', 'eeeee', 'V' ],
			[ 'fr', '2012-06-01', 'eeeeee', 've' ],
			[ 'fr', '2012-06-01', 'eeeeeee', '' ],

			# test: format stand-alone weekday

			[ 'en', '2012-06-01', 'c', '5' ],
			[ 'en', '2012-06-01', 'cc', '' ],
			[ 'en', '2012-06-01', 'ccc', 'Fri' ],
			[ 'en', '2012-06-01', 'cccc', 'Friday' ],
			[ 'en', '2012-06-01', 'ccccc', 'F' ],
			[ 'en', '2012-06-01', 'cccccc', 'Fr' ],
			[ 'en', '2012-06-01', 'ccccccc', '' ],

			# test: format stand-alone weekday in french

			[ 'fr', '2012-06-01', 'c', '5' ],
			[ 'fr', '2012-06-01', 'cc', '' ],
			[ 'fr', '2012-06-01', 'ccc', 'Ven.' ],
			[ 'fr', '2012-06-01', 'cccc', 'Vendredi' ],
			[ 'fr', '2012-06-01', 'ccccc', 'V' ],
			[ 'fr', '2012-06-01', 'cccccc', 'Ve' ],
			[ 'fr', '2012-06-01', 'ccccccc', '' ],

			# test: format period

			[ 'en', '2012-06-01 00:00:00', 'a', 'AM' ],
			[ 'en', '2012-06-01 06:00:00', 'a', 'AM' ],
			[ 'en', '2012-06-01 12:00:00', 'a', 'PM' ],
			[ 'en', '2012-06-01 18:00:00', 'a', 'PM' ],

			# test: format period in french

			[ 'fr', '2012-06-01 00:00:00', 'a', 'AM' ],
			[ 'fr', '2012-06-01 06:00:00', 'a', 'AM' ],
			[ 'fr', '2012-06-01 12:00:00', 'a', 'PM' ],
			[ 'fr', '2012-06-01 18:00:00', 'a', 'PM' ],

			# test: format hour 12

			[ 'en', '2012-06-01 00:00:00', 'h', '0' ],
			[ 'en', '2012-06-01 06:00:00', 'h', '6' ],
			[ 'en', '2012-06-01 12:00:00', 'h', '12' ],
			[ 'en', '2012-06-01 18:00:00', 'h', '6' ],

			[ 'en', '2012-06-01 00:00:00', 'hh', '00' ],
			[ 'en', '2012-06-01 06:00:00', 'hh', '06' ],
			[ 'en', '2012-06-01 12:00:00', 'hh', '12' ],
			[ 'en', '2012-06-01 18:00:00', 'hh', '06' ],

			[ 'en', '2012-06-01 18:00:00', 'hhh', '' ],

			# test: format hour 24

			[ 'en', '2012-06-01 00:00:00', 'H', '0' ],
			[ 'en', '2012-06-01 06:00:00', 'H', '6' ],
			[ 'en', '2012-06-01 12:00:00', 'H', '12' ],
			[ 'en', '2012-06-01 18:00:00', 'H', '18' ],

			[ 'en', '2012-06-01 00:00:00', 'HH', '00' ],
			[ 'en', '2012-06-01 06:00:00', 'HH', '06' ],
			[ 'en', '2012-06-01 12:00:00', 'HH', '12' ],
			[ 'en', '2012-06-01 18:00:00', 'HH', '18' ],

			[ 'en', '2012-06-01 18:00:00', 'HHH', '' ],

			# test: format hour in period

			[ 'en', '2012-06-01 00:00:00', 'K', '0' ],
			[ 'en', '2012-06-01 00:00:00', 'KK', '00' ],
			[ 'en', '2012-06-01 00:00:00', 'KKK', '' ],
			[ 'en', '2012-06-01 00:00:00', 'KKKK', '' ],
			[ 'en', '2012-06-01 00:00:00', 'KKKKK', '' ],
			[ 'en', '2012-06-01 06:00:00', 'K', '6' ],
			[ 'en', '2012-06-01 06:00:00', 'KK', '06' ],
			[ 'en', '2012-06-01 06:00:00', 'KKK', '' ],
			[ 'en', '2012-06-01 06:00:00', 'KKKK', '' ],
			[ 'en', '2012-06-01 06:00:00', 'KKKKK', '' ],
			[ 'en', '2012-06-01 12:00:00', 'K', '0' ],
			[ 'en', '2012-06-01 12:00:00', 'KK', '00' ],
			[ 'en', '2012-06-01 12:00:00', 'KKK', '' ],
			[ 'en', '2012-06-01 12:00:00', 'KKKK', '' ],
			[ 'en', '2012-06-01 12:00:00', 'KKKKK', '' ],
			[ 'en', '2012-06-01 18:00:00', 'K', '6' ],
			[ 'en', '2012-06-01 18:00:00', 'KK', '06' ],
			[ 'en', '2012-06-01 18:00:00', 'KKK', '' ],
			[ 'en', '2012-06-01 18:00:00', 'KKKK', '' ],
			[ 'en', '2012-06-01 18:00:00', 'KKKKK', '' ],

			# test: format hour in day

			[ 'en', '2012-06-01 00:00:00', 'k', '24' ],
			[ 'en', '2012-06-01 00:00:00', 'kk', '24' ],
			[ 'en', '2012-06-01 00:00:00', 'kkk', '' ],
			[ 'en', '2012-06-01 00:00:00', 'kkkk', '' ],
			[ 'en', '2012-06-01 00:00:00', 'kkkkk', '' ],
			[ 'en', '2012-06-01 06:00:00', 'k', '6' ],
			[ 'en', '2012-06-01 06:00:00', 'kk', '06' ],
			[ 'en', '2012-06-01 06:00:00', 'kkk', '' ],
			[ 'en', '2012-06-01 06:00:00', 'kkkk', '' ],
			[ 'en', '2012-06-01 06:00:00', 'kkkkk', '' ],
			[ 'en', '2012-06-01 12:00:00', 'k', '12' ],
			[ 'en', '2012-06-01 12:00:00', 'kk', '12' ],
			[ 'en', '2012-06-01 12:00:00', 'kkk', '' ],
			[ 'en', '2012-06-01 12:00:00', 'kkkk', '' ],
			[ 'en', '2012-06-01 12:00:00', 'kkkkk', '' ],
			[ 'en', '2012-06-01 18:00:00', 'k', '18' ],
			[ 'en', '2012-06-01 18:00:00', 'kk', '18' ],
			[ 'en', '2012-06-01 18:00:00', 'kkk', '' ],
			[ 'en', '2012-06-01 18:00:00', 'kkkk', '' ],
			[ 'en', '2012-06-01 18:00:00', 'kkkkk', '' ],

			# test: format minute

			[ 'en', '2012-06-01 23:01:45', 'm', '1' ],
			[ 'en', '2012-06-01 23:01:45', 'mm', '01' ],
			[ 'en', '2012-06-01 23:12:45', 'm', '12' ],
			[ 'en', '2012-06-01 23:12:45', 'mm', '12' ],

			# test: format second

			[ 'en', '2012-06-01 23:01:02', 's', '2' ],
			[ 'en', '2012-06-01 23:01:02', 'ss', '02' ],
			[ 'en', '2012-06-01 23:12:45', 's', '45' ],
			[ 'en', '2012-06-01 23:12:45', 'ss', '45' ],

			# test: format zone

			[ 'en', '2012-06-01 01:23:45+0200', 'z', 'GMT+0200' ],

			# test: format quarter one figure

			[ 'en', '2012-01-13', 'Q', '1' ],
			[ 'en', '2012-02-13', 'Q', '1' ],
			[ 'en', '2012-03-13', 'Q', '1' ],
			[ 'en', '2012-04-13', 'Q', '2' ],
			[ 'en', '2012-05-13', 'Q', '2' ],
			[ 'en', '2012-06-13', 'Q', '2' ],
			[ 'en', '2012-07-13', 'Q', '3' ],
			[ 'en', '2012-08-13', 'Q', '3' ],
			[ 'en', '2012-09-13', 'Q', '3' ],
			[ 'en', '2012-10-13', 'Q', '4' ],
			[ 'en', '2012-11-13', 'Q', '4' ],
			[ 'en', '2012-12-13', 'Q', '4' ],

			# test: format quarter two figures

			[ 'en', '2012-01-13', 'QQ', '01' ],
			[ 'en', '2012-04-13', 'QQ', '02' ],
			[ 'en', '2012-07-13', 'QQ', '03' ],
			[ 'en', '2012-10-13', 'QQ', '04' ],

			# test: format quarter abbreviated

			[ 'en', '2012-01-13', 'QQQ', 'Q1' ],
			[ 'en', '2012-04-13', 'QQQ', 'Q2' ],
			[ 'en', '2012-07-13', 'QQQ', 'Q3' ],
			[ 'en', '2012-10-13', 'QQQ', 'Q4' ],

			# test: format quarter abbreviated in french

			[ 'fr', '2012-01-13', 'QQQ', 'T1' ],
			[ 'fr', '2012-04-13', 'QQQ', 'T2' ],
			[ 'fr', '2012-07-13', 'QQQ', 'T3' ],
			[ 'fr', '2012-10-13', 'QQQ', 'T4' ],

			# test: format quarter wide

			[ 'en', '2012-01-13', 'QQQQ', '1st quarter' ],
			[ 'en', '2012-04-13', 'QQQQ', '2nd quarter' ],
			[ 'en', '2012-07-13', 'QQQQ', '3rd quarter' ],
			[ 'en', '2012-10-13', 'QQQQ', '4th quarter' ],

			# test: format quarter wide in french

			[ 'fr', '2012-01-13', 'QQQQ', '1er trimestre' ],
			[ 'fr', '2012-04-13', 'QQQQ', '2e trimestre' ],
			[ 'fr', '2012-07-13', 'QQQQ', '3e trimestre' ],
			[ 'fr', '2012-10-13', 'QQQQ', '4e trimestre' ],

			# test: format quarter invalid wide
			[ 'fr', '2012-10-13', 'QQQQQ', '' ],

			# test: format stand-alone quarter one figure

			[ 'en', '2012-01-13', 'q', '1' ],
			[ 'en', '2012-02-13', 'q', '1' ],
			[ 'en', '2012-03-13', 'q', '1' ],
			[ 'en', '2012-04-13', 'q', '2' ],
			[ 'en', '2012-05-13', 'q', '2' ],
			[ 'en', '2012-06-13', 'q', '2' ],
			[ 'en', '2012-07-13', 'q', '3' ],
			[ 'en', '2012-08-13', 'q', '3' ],
			[ 'en', '2012-09-13', 'q', '3' ],
			[ 'en', '2012-10-13', 'q', '4' ],
			[ 'en', '2012-11-13', 'q', '4' ],
			[ 'en', '2012-12-13', 'q', '4' ],

			# test: format quarter two figures

			[ 'en', '2012-01-13', 'qq', '01' ],
			[ 'en', '2012-04-13', 'qq', '02' ],
			[ 'en', '2012-07-13', 'qq', '03' ],
			[ 'en', '2012-10-13', 'qq', '04' ],

			# test: format quarter abbreviated

			[ 'en', '2012-01-13', 'qqq', 'Q1' ],
			[ 'en', '2012-04-13', 'qqq', 'Q2' ],
			[ 'en', '2012-07-13', 'qqq', 'Q3' ],
			[ 'en', '2012-10-13', 'qqq', 'Q4' ],

			# test: format quarter abbreviated in french

			[ 'fr', '2012-01-13', 'qqq', 'T1' ],
			[ 'fr', '2012-04-13', 'qqq', 'T2' ],
			[ 'fr', '2012-07-13', 'qqq', 'T3' ],
			[ 'fr', '2012-10-13', 'qqq', 'T4' ],

			# test: format quarter wide

			[ 'en', '2012-01-13', 'qqqq', '1st quarter' ],
			[ 'en', '2012-04-13', 'qqqq', '2nd quarter' ],
			[ 'en', '2012-07-13', 'qqqq', '3rd quarter' ],
			[ 'en', '2012-10-13', 'qqqq', '4th quarter' ],

			# test: format quarter wide in french

			[ 'fr', '2012-01-13', 'qqqq', '1er trimestre' ],
			[ 'fr', '2012-04-13', 'qqqq', '2e trimestre' ],
			[ 'fr', '2012-07-13', 'qqqq', '3e trimestre' ],
			[ 'fr', '2012-10-13', 'qqqq', '4e trimestre' ],

			# test: format width(full|long|medium|short)

			[ 'en', '2013-11-02 22:23:45', 'full', 'Saturday, November 2, 2013 at 10:23:45 PM CET' ],
			[ 'en', '2013-11-02 22:23:45', 'long', 'November 2, 2013 at 10:23:45 PM CET' ],
			[ 'en', '2013-11-02 22:23:45', 'medium', 'Nov 2, 2013, 10:23:45 PM' ],
			[ 'en', '2013-11-02 22:23:45', 'short', '11/2/13, 10:23 PM' ],

			# test: format width(full|long|medium|short) in french

			[ 'fr', '2013-11-02 22:23:45', 'full', 'samedi 2 novembre 2013 à 22:23:45 CET' ],
			[ 'fr', '2013-11-02 22:23:45', 'long', '2 novembre 2013 à 22:23:45 CET' ],
			[ 'fr', '2013-11-02 22:23:45', 'medium', '2 nov. 2013 à 22:23:45' ],
			[ 'fr', '2013-11-02 22:23:45', 'short', '02/11/2013 22:23' ],

			[ 'fr', '2016-06-06', "''y 'Madonna' y 'Yay", "'2016 Madonna 2016 Yay" ],

		];
	}

	/*
	 * Format datetime
	 */

	/*
	public function test_format_datetime()
	{
		$f = Locale::from('fr')->date_formatter;
		$t = new DateTime('2013-10-26 22:08:30', 'Europe/Paris');

		$this->assertEquals('26 oct. 2013 22:08:30', $f->format_datetime($t));
		$this->assertEquals('26 oct. 2013 22:08:30', $f->format_datetime($t, null, null));
		$this->assertEquals('26 oct. 2013 22:08:30', $f->format_datetime($t, 'default', 'default'));
	}
	*/

	/**
	 * @dataProvider provide_test_format_with_skeleton
	 *
	 * @param string $skeleton
	 * @param string $pattern
	 * @param string $expected_result
	 */
	public function test_format_with_skeleton($skeleton, $pattern, $expected_result)
	{
		$formatter = self::$formatters['fr'];
		$datetime = new \DateTime('2013-10-26 22:08:30', new \DateTimeZone('Europe/Paris'));

		$result = $formatter->format($datetime, ':' . $skeleton);

		$this->assertEquals($formatter($datetime, $pattern), $result);
		$this->assertEquals($expected_result, $result);
	}

	public function provide_test_format_with_skeleton()
	{
		return [

			[ "d", "d", "26" ],
			[ "Ed", "E d", "sam. 26" ],
			[ "Ehm", "E h:mm a", "sam. 10:08 PM" ],
			[ "EHm", "E HH:mm", "sam. 22:08" ],
			[ "Ehms", "E h:mm:ss a", "sam. 10:08:30 PM" ],
			[ "EHms", "E HH:mm:ss", "sam. 22:08:30" ],
			[ "Gy", "y G", "2013 ap. J.-C." ],
			[ "GyMMM", "MMM y G", "oct. 2013 ap. J.-C." ],
			[ "GyMMMd", "d MMM y G", "26 oct. 2013 ap. J.-C." ],
			[ "GyMMMEd", "E d MMM y G", "sam. 26 oct. 2013 ap. J.-C." ],
			[ "h", "h a", "10 PM" ],
			[ "H", "HH 'h'", "22 h" ],
			[ "hm", "h:mm a", "10:08 PM" ],
			[ "Hm", "HH:mm", "22:08" ],
			[ "hms", "h:mm:ss a", "10:08:30 PM" ],
			[ "Hms", "HH:mm:ss", "22:08:30" ],
			[ "M", "L", "10" ],
			[ "Md", "d/M", "26/10" ],
			[ "MEd", "E d/M", "sam. 26/10" ],
			[ "MMM", "LLL", "Oct." ],
			[ "MMMd", "d MMM", "26 oct." ],
			[ "MMMEd", "E d MMM", "sam. 26 oct." ],
			[ "ms", "mm:ss", "08:30" ],
			[ "y", "y", "2013" ],
			[ "yM", "M/y", "10/2013" ],
			[ "yMd", "d/M/y", "26/10/2013" ],
			[ "yMEd", "E d/M/y", "sam. 26/10/2013" ],
			[ "yMMM", "MMM y", "oct. 2013" ],
			[ "yMMMd", "d MMM y", "26 oct. 2013" ],
			[ "yMMMEd", "E d MMM y", "sam. 26 oct. 2013" ],
			[ "yQQQ", "QQQ y", "T4 2013" ],
			[ "yQQQQ", "QQQQ y", "4e trimestre 2013" ]

		];
	}
}
