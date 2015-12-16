<?php namespace InfinityNext\Eightdown\Facades;

use Illuminate\Support\Facades\Facade;

class EightdownFacade extends Facade {
	
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'markdown';
	}
	
}