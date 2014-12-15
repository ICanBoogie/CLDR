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

use ICanBoogie\DateTime;

class DateTimeFormatterTest extends \PHPUnit_Framework_TestCase
{
	static private $formatters = array();

	static public function setupBeforeClass()
	{
		$repository = get_repository();

		self::$formatters = array
		(
			'en' => new DateTimeFormatter($repository->locales['en']->calendar),
			'fr' => new DateTimeFormatter($repository->locales['fr']->calendar)
		);
	}

	public function test_get_calendar()
	{
		$this->assertInstanceOf('ICanBoogie\CLDR\Calendar', self::$formatters['en']->calendar);
	}

	/**
	 * @dataProvider provide_test_format
	 */
	public function test_format($locale_id, $datetime, $format, $expected)
	{
		$formatter = self::$formatters[$locale_id];

		$this->assertEquals($expected, $formatter($datetime, $format));
	}

	public function provide_test_format()
	{
		return array
		(
			# test: format year one figure

			array('en', '0005-01-01', 'y', '5'),
			array('en', '0005-01-01', 'yy', '05'),
			array('en', '0005-01-01', 'yyy', '005'),
			array('en', '0005-01-01', 'yyyy', '0005'),
			array('en', '0005-01-01', 'yyyyy', '00005'),

			# test: format year two figures

			array('en', '0045-01-01', 'y', '45'),
			array('en', '0045-01-01', 'yy', '45'),
			array('en', '0045-01-01', 'yyy', '045'),
			array('en', '0045-01-01', 'yyyy', '0045'),
			array('en', '0045-01-01', 'yyyyy', '00045'),

			# test: format year three figures

			array('en', '0345-01-01', 'y', '345'),
			array('en', '0345-01-01', 'yy', '45'),
			array('en', '0345-01-01', 'yyy', '345'),
			array('en', '0345-01-01', 'yyyy', '0345'),
			array('en', '0345-01-01', 'yyyyy', '00345'),

			# test: format year four figures

			array('en', '2345-01-01', 'y', '2345'),
			array('en', '2345-01-01', 'yy', '45'),
			array('en', '2345-01-01', 'yyy', '2345'),
			array('en', '2345-01-01', 'yyyy', '2345'),
			array('en', '2345-01-01', 'yyyyy', '02345'),

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

			array('en', '2012-02-13', 'M', '2'),
			array('en', '2012-02-13', 'MM', '02'),
			array('en', '2012-02-13', 'MMM', 'Feb'),
			array('en', '2012-02-13', 'MMMM', 'February'),
			array('en', '2012-02-13', 'MMMMM', 'F'),

			# test: format month in french

			array('fr', '2012-02-13', 'M', '2'),
			array('fr', '2012-02-13', 'MM', '02'),
			array('fr', '2012-02-13', 'MMM', 'févr.'),
			array('fr', '2012-02-13', 'MMMM', 'février'),
			array('fr', '2012-02-13', 'MMMMM', 'F'),

			# test: format stand-alone month

			array('en', '2012-02-13', 'L', '2'),
			array('en', '2012-02-13', 'LL', '02'),
			array('en', '2012-02-13', 'LLL', 'Feb'),
			array('en', '2012-02-13', 'LLLL', 'February'),
			array('en', '2012-02-13', 'LLLLL', 'F'),

			# test: format stand-alone month in french

			array('fr', '2012-02-13', 'L', '2'),
			array('fr', '2012-02-13', 'LL', '02'),
			array('fr', '2012-02-13', 'LLL', 'Févr.'),
			array('fr', '2012-02-13', 'LLLL', 'Février'),
			array('fr', '2012-02-13', 'LLLLL', 'F'),

			# test: fromat week of year

			array('en', '2012-01-01', 'w', '52'),
			array('en', '2012-01-02', 'w', '1'),
			array('en', '2012-01-02', 'ww', '01'),
			array('en', '2012-12-30', 'w', '52'),
			array('en', '2012-12-30', 'ww', '52'),

			# test: format week of month

			array('en', '2012-01-01', 'W', 1),
			array('en', '2012-01-02', 'W', 1),
			array('en', '2012-01-09', 'W', 2),
			array('en', '2012-01-16', 'W', 3),
			array('en', '2012-01-23', 'W', 4),
			array('en', '2012-01-30', 'W', 5),

			# test: format day of the month

			array('en', '2012-01-01', 'd', '1'),
			array('en', '2012-01-01', 'dd', '01'),
			array('en', '2012-01-13', 'd', '13'),
			array('en', '2012-01-13', 'dd', '13'),

			# test: format day of the year

			array('en', '2012-01-01', 'D', '1'),
			array('en', '2012-01-01', 'DD', '01'),
			array('en', '2012-01-01', 'DDD', '001'),
			array('en', '2012-01-13', 'DD', '13'),
			array('en', '2012-01-13', 'DDD', '013'),
			array('en', '2012-06-13', 'DD', '165'),
			array('en', '2012-06-13', 'DDD', '165'),

			# test: format day of week
			/* FIXME
			array('en', '2012-06-01', 'F', '1'),
			array('en', '2012-06-03', 'F', '3'),
			array('en', '2012-06-05', 'F', '5'),
			*/

			# test: format weekday

			array('en', '2012-06-01', 'E', 'Fri'),
			array('en', '2012-06-01', 'EE', 'Fri'),
			array('en', '2012-06-01', 'EEE', 'Fri'),
			array('en', '2012-06-01', 'EEEE', 'Friday'),
			array('en', '2012-06-01', 'EEEEE', 'F'),
			array('en', '2012-06-01', 'EEEEEE', 'Fr'),

			# test: format weekday in french

			array('fr', '2012-06-01', 'E', 'ven.'),
			array('fr', '2012-06-01', 'EE', 'ven.'),
			array('fr', '2012-06-01', 'EEE', 'ven.'),
			array('fr', '2012-06-01', 'EEEE', 'vendredi'),
			array('fr', '2012-06-01', 'EEEEE', 'V'),
			array('fr', '2012-06-01', 'EEEEEE', 've'),

			# test: format stand-alone weekday

			array('en', '2012-06-01', 'c', '5'),
			array('en', '2012-06-01', 'cc', ''),
			array('en', '2012-06-01', 'ccc', 'Fri'),
			array('en', '2012-06-01', 'cccc', 'Friday'),
			array('en', '2012-06-01', 'ccccc', 'F'),
			array('en', '2012-06-01', 'cccccc', 'Fr'),

			# test: format stand-alone weekday in french

			array('fr', '2012-06-01', 'c', '5'),
			array('fr', '2012-06-01', 'cc', ''),
			array('fr', '2012-06-01', 'ccc', 'Ven.'),
			array('fr', '2012-06-01', 'cccc', 'Vendredi'),
			array('fr', '2012-06-01', 'ccccc', 'V'),
			array('fr', '2012-06-01', 'cccccc', 'Ve'),

			# test: format period

			array('en', '2012-06-01 00:00:00', 'a', 'AM'),
			array('en', '2012-06-01 06:00:00', 'a', 'AM'),
			array('en', '2012-06-01 12:00:00', 'a', 'PM'),
			array('en', '2012-06-01 18:00:00', 'a', 'PM'),

			# test: format period in french

			array('fr', '2012-06-01 00:00:00', 'a', 'AM'),
			array('fr', '2012-06-01 06:00:00', 'a', 'AM'),
			array('fr', '2012-06-01 12:00:00', 'a', 'PM'),
			array('fr', '2012-06-01 18:00:00', 'a', 'PM'),

			# test: format hour 12

			array('en', '2012-06-01 00:00:00', 'h', '0'),
			array('en', '2012-06-01 06:00:00', 'h', '6'),
			array('en', '2012-06-01 12:00:00', 'h', '12'),
			array('en', '2012-06-01 18:00:00', 'h', '6'),

			array('en', '2012-06-01 00:00:00', 'hh', '00'),
			array('en', '2012-06-01 06:00:00', 'hh', '06'),
			array('en', '2012-06-01 12:00:00', 'hh', '12'),
			array('en', '2012-06-01 18:00:00', 'hh', '06'),

			# test: format hour 24

			array('en', '2012-06-01 00:00:00', 'H', '0'),
			array('en', '2012-06-01 06:00:00', 'H', '6'),
			array('en', '2012-06-01 12:00:00', 'H', '12'),
			array('en', '2012-06-01 18:00:00', 'H', '18'),

			array('en', '2012-06-01 00:00:00', 'HH', '00'),
			array('en', '2012-06-01 06:00:00', 'HH', '06'),
			array('en', '2012-06-01 12:00:00', 'HH', '12'),
			array('en', '2012-06-01 18:00:00', 'HH', '18'),

			# test: format minute

			array('en', '2012-06-01 23:01:45', 'm', '1'),
			array('en', '2012-06-01 23:01:45', 'mm', '01'),
			array('en', '2012-06-01 23:12:45', 'm', '12'),
			array('en', '2012-06-01 23:12:45', 'mm', '12'),

			# test: format second

			array('en', '2012-06-01 23:01:02', 's', '2'),
			array('en', '2012-06-01 23:01:02', 'ss', '02'),
			array('en', '2012-06-01 23:12:45', 's', '45'),
			array('en', '2012-06-01 23:12:45', 'ss', '45'),

			# test: format zone

			array('en', '2012-06-01 01:23:45+0200', 'z', 'GMT+0200'),

			# test: format quarter one figure

			array('en', '2012-01-13', 'Q', 1),
			array('en', '2012-02-13', 'Q', 1),
			array('en', '2012-03-13', 'Q', 1),
			array('en', '2012-04-13', 'Q', 2),
			array('en', '2012-05-13', 'Q', 2),
			array('en', '2012-06-13', 'Q', 2),
			array('en', '2012-07-13', 'Q', 3),
			array('en', '2012-08-13', 'Q', 3),
			array('en', '2012-09-13', 'Q', 3),
			array('en', '2012-10-13', 'Q', 4),
			array('en', '2012-11-13', 'Q', 4),
			array('en', '2012-12-13', 'Q', 4),

			# test: format quarter two figures

			array('en', '2012-01-13', 'QQ', '01'),
			array('en', '2012-04-13', 'QQ', '02'),
			array('en', '2012-07-13', 'QQ', '03'),
			array('en', '2012-10-13', 'QQ', '04'),

			# test: format quarter abbreviated

			array('en', '2012-01-13', 'QQQ', 'Q1'),
			array('en', '2012-04-13', 'QQQ', 'Q2'),
			array('en', '2012-07-13', 'QQQ', 'Q3'),
			array('en', '2012-10-13', 'QQQ', 'Q4'),

			# test: format quarter abbreviated in french

			array('fr', '2012-01-13', 'QQQ', 'T1'),
			array('fr', '2012-04-13', 'QQQ', 'T2'),
			array('fr', '2012-07-13', 'QQQ', 'T3'),
			array('fr', '2012-10-13', 'QQQ', 'T4'),

			# test: format quarter wide

			array('en', '2012-01-13', 'QQQQ', '1st quarter'),
			array('en', '2012-04-13', 'QQQQ', '2nd quarter'),
			array('en', '2012-07-13', 'QQQQ', '3rd quarter'),
			array('en', '2012-10-13', 'QQQQ', '4th quarter'),

			# test: format quarter wide in french

			array('fr', '2012-01-13', 'QQQQ', '1er trimestre'),
			array('fr', '2012-04-13', 'QQQQ', '2e trimestre'),
			array('fr', '2012-07-13', 'QQQQ', '3e trimestre'),
			array('fr', '2012-10-13', 'QQQQ', '4e trimestre'),

			# test: format stand-alone quarter one figure

			array('en', '2012-01-13', 'q', 1),
			array('en', '2012-02-13', 'q', 1),
			array('en', '2012-03-13', 'q', 1),
			array('en', '2012-04-13', 'q', 2),
			array('en', '2012-05-13', 'q', 2),
			array('en', '2012-06-13', 'q', 2),
			array('en', '2012-07-13', 'q', 3),
			array('en', '2012-08-13', 'q', 3),
			array('en', '2012-09-13', 'q', 3),
			array('en', '2012-10-13', 'q', 4),
			array('en', '2012-11-13', 'q', 4),
			array('en', '2012-12-13', 'q', 4),

			# test: format quarter two figures

			array('en', '2012-01-13', 'qq', '01'),
			array('en', '2012-04-13', 'qq', '02'),
			array('en', '2012-07-13', 'qq', '03'),
			array('en', '2012-10-13', 'qq', '04'),

			# test: format quarter abbreviated

			array('en', '2012-01-13', 'qqq', 'Q1'),
			array('en', '2012-04-13', 'qqq', 'Q2'),
			array('en', '2012-07-13', 'qqq', 'Q3'),
			array('en', '2012-10-13', 'qqq', 'Q4'),

			# test: format quarter abbreviated in french

			array('fr', '2012-01-13', 'qqq', 'T1'),
			array('fr', '2012-04-13', 'qqq', 'T2'),
			array('fr', '2012-07-13', 'qqq', 'T3'),
			array('fr', '2012-10-13', 'qqq', 'T4'),

			# test: format quarter wide

			array('en', '2012-01-13', 'qqqq', '1st quarter'),
			array('en', '2012-04-13', 'qqqq', '2nd quarter'),
			array('en', '2012-07-13', 'qqqq', '3rd quarter'),
			array('en', '2012-10-13', 'qqqq', '4th quarter'),

			# test: format quarter wide in french

			array('fr', '2012-01-13', 'qqqq', '1er trimestre'),
			array('fr', '2012-04-13', 'qqqq', '2e trimestre'),
			array('fr', '2012-07-13', 'qqqq', '3e trimestre'),
			array('fr', '2012-10-13', 'qqqq', '4e trimestre'),

			# test: format width(full|long|medium|short)

			array('en', '2013-11-02 22:23:45', 'full', 'Saturday, November 2, 2013 at 10:23:45 PM CET'),
			array('en', '2013-11-02 22:23:45', 'long', 'November 2, 2013 at 10:23:45 PM CET'),
			array('en', '2013-11-02 22:23:45', 'medium', 'Nov 2, 2013, 10:23:45 PM'),
			array('en', '2013-11-02 22:23:45', 'short', '11/2/13, 10:23 PM'),

			# test: format width(full|long|medium|short) in french

			array('fr', '2013-11-02 22:23:45', 'full', 'samedi 2 novembre 2013 22:23:45 CET'),
			array('fr', '2013-11-02 22:23:45', 'long', '2 novembre 2013 22:23:45 CET'),
			array('fr', '2013-11-02 22:23:45', 'medium', '2 nov. 2013 22:23:45'),
			array('fr', '2013-11-02 22:23:45', 'short', '02/11/2013 22:23'),
		);
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
	 */
	public function test_format_with_skeleton($skeleton, $pattern, $expected_result)
	{
		$formatter = self::$formatters['fr'];
		$datetime = new DateTime('2013-10-26 22:08:30', 'Europe/Paris');

		$result = $formatter->format($datetime, ':' . $skeleton);

		$this->assertEquals($formatter($datetime, $pattern), $result);
		$this->assertEquals($expected_result, $result);
	}

	public function provide_test_format_with_skeleton()
	{
		return array
		(
			array("d", "d", "26"),
			array("Ed", "E d", "sam. 26"),
			array("Ehm", "E h:mm a", "sam. 10:08 PM"),
			array("EHm", "E HH:mm", "sam. 22:08"),
			array("Ehms", "E h:mm:ss a", "sam. 10:08:30 PM"),
			array("EHms", "E HH:mm:ss", "sam. 22:08:30"),
			array("Gy", "y G", "2013 ap. J.-C."),
			array("GyMMM", "MMM y G", "oct. 2013 ap. J.-C."),
			array("GyMMMd", "d MMM y G", "26 oct. 2013 ap. J.-C."),
			array("GyMMMEd", "E d MMM y G", "sam. 26 oct. 2013 ap. J.-C."),
			array("h", "h a", "10 PM"),
			array("H", "HH 'h'", "22 h"),
			array("hm", "h:mm a", "10:08 PM"),
			array("Hm", "HH:mm", "22:08"),
			array("hms", "h:mm:ss a", "10:08:30 PM"),
			array("Hms", "HH:mm:ss", "22:08:30"),
			array("M", "L", "10"),
			array("Md", "d/M", "26/10"),
			array("MEd", "E d/M", "sam. 26/10"),
			array("MMM", "LLL", "Oct."),
			array("MMMd", "d MMM", "26 oct."),
			array("MMMEd", "E d MMM", "sam. 26 oct."),
			array("ms", "mm:ss", "08:30"),
			array("y", "y", "2013"),
			array("yM", "M/y", "10/2013"),
			array("yMd", "d/M/y", "26/10/2013"),
			array("yMEd", "E d/M/y", "sam. 26/10/2013"),
			array("yMMM", "MMM y", "oct. 2013"),
			array("yMMMd", "d MMM y", "26 oct. 2013"),
			array("yMMMEd", "E d MMM y", "sam. 26 oct. 2013"),
			array("yQQQ", "QQQ y", "T4 2013"),
			array("yQQQQ", "QQQQ y", "4e trimestre 2013")
		);
	}
}
