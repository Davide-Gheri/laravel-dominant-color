<?php

namespace Ghero\DominantColor\Facades;

use Illuminate\Support\Facades\Facade;

class DominantColor extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return \Ghero\DominantColor\DominantColor::class;
	}
}