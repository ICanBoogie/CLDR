<?php

namespace ICanBoogie\CLDR;

$hooks = __NAMESPACE__ . '\Hooks::';

return [

	'prototypes' => [

		'ICanBoogie\Core::lazy_get_cldr_provider' => $hooks . 'get_cldr_provider',
		'ICanBoogie\Core::lazy_get_cldr' => $hooks . 'get_cldr',
		'ICanBoogie\Core::get_locale' => $hooks . 'get_locale',
		'ICanBoogie\Core::set_locale' => $hooks . 'set_locale'

	]

];
