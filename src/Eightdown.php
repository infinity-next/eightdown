<?php namespace InfinityNext\Eightdown;

use InfinityNext\Eightdown\Contracts\Eightdown as EightdownContract;

use InfinityNext\Eightdown\Traits\ParsedownExtensibility;
use InfinityNext\Eightdown\Traits\ExtendQuoteKeepArrows;

use InfinityNext\Eightdown\Parsedown as MarkdownEngine;

class Eightdown extends MarkdownEngine implements EightdownContract {
	
	use ParsedownExtensibility,
		ExtendQuoteKeepArrows;
	
	/**
	 * Parses input text through our markup engine.
	 *
	 * @param  string  $text
	 * @return string
	 */
	public function parse($text)
	{
		return static::text($text);
	}
	
}