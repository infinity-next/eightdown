<?php namespace InfinityNext\Eightdown\Contracts;

interface Eightdown {
	
	/**
	 * Configures the instance using an array.
	 *
	 * @param  array|string  $options  Options to set if array, or option value if string.
	 * @return Eightdown|mixed
	 */
	public function config($options);
	
	/**
	 * Parses input text through our markup engine.
	 *
	 * @param  string  $text
	 * @return string
	 */
	public function parse($text);
	
}