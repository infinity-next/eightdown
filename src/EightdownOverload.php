<?php namespace InfinityNext\Eightdown;

use InfinityNext\Eightdown\Traits\PreserveQuoteArrows;

use Parsedown;

class EightdownOverload extends Parsedown {
	
	use PreserveQuoteArrows;
	
	/**
	 * Parses text through Parsedown.
	 *
	 * @param string
	 * @return string
	 */
	function parse($text)
	{
		return parent::text($text);
	}
	
}