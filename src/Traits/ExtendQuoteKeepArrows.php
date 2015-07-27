<?php namespace InfinityNext\Eightdown\Traits;

trait ExtendQuoteKeepArrows {
	
	/**
	 * Injects a greater-than sign at the start of a quote block.
	 *
	 * @param  array  $Line
	 * @return array
	 */
	protected function blockQuote($Line)
	{
		if (preg_match('/^>[ ]?(.*)/', $Line['text'], $matches))
		{
			$Block = array(
				'element' => array(
					'name' => 'blockquote',
					'handler' => 'lines',
					'text' => (array) ("&gt;" . $matches[1]),
				),
			);
			
			return $Block;
		}
	}
	
	/**
	 * Injects a greater-than sign at the beginning of each line in a quote block.
	 *
	 * @param  array  $Line
	 * @return array
	 */
	protected function blockQuoteContinue($Line, array $Block)
	{
		if ($Line['text'][0] === '>' and preg_match('/^>[ ]?(.*)/', $Line['text'], $matches))
		{
			if (isset($Block['interrupted']))
			{
				$Block['element']['text'] []= '';
				
				unset($Block['interrupted']);
			}
			
			$Block['element']['text'] []= "&gt;" . $matches[1];
			
			return $Block;
		}
		
		if ( ! isset($Block['interrupted']))
		{
			$Block['element']['text'] []= "&gt;" . $Line['text'];
			
			return $Block;
		}
	}
	
}