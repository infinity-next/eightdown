<?php namespace InfinityNext\Eightdown\Contracts;

interface Eightdown {
	
	/**
	 * Parses input text through our markup engine.
	 *
	 * @param  string  $text
	 * @return string
	 */
	public function parse($text);
	
}