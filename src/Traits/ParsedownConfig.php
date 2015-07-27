<?php namespace InfinityNext\Eightdown\Traits;

trait ParsedownConfig {
	
	/**
	 * The compiled set of options.
	 * 
	 * @var array
	 */
	protected $EightdownConfig = [
		'general' => [
			'keepLineBreaks' => false,
			'parseHTML'      => true,
			'parseURL'       => true,
		],
		
		'disable' => [
			
		],
		
		'markup' => [
			'quote' => [
				'keepSigns' => false,
			],
		],
	];
	
	/**
	 * Configures the instance using an array.
	 *
	 * @param  array|string  $options  Options to set if array, or option value if string.
	 * @return Eightdown|mixed
	 */
	public function config($options)
	{
		if (is_array($options))
		{
			$this->EightdownConfig = array_merge_recursive($this->EightdownConfig, $options);
			
			// Set generic parsedown config items.
			$this->setBreaksEnabled( !!$this->config('general.keepLineBreaks') )
				->setMarkupEscaped( !$this->config('general.parseHTML') )
				->setUrlsLinked( !!$this->config('general.parseURL') );
			
			// Disable items
			foreach ($options['disable'] as $disabledMarkup)
			{
				$this->removeBlockByName($disabledMarkup);
				$this->removeInlineByName($disabledMarkup);
			}
			
			foreach ($options['enable'] as $enabledMarkup)
			{
				$enableMarkupMethod = camel_case("enable_markup_{$enabledMarkup}");
				$this->{$enableMarkupMethod}();
			}
			
			return $this;
		}
		else if (is_string($options))
		{
			return array_get($this->EightdownConfig, $options);
		}
	}
	
	
	/**
	 * Injects a greater-than sign at the start of a quote block.
	 *
	 * @param  array  $Line
	 * @return array
	 */
	protected function blockQuote($Line)
	{
		if (!$this->config('markup.quote.keepSigns'))
		{
			return parent::blockQuote($Line);
		}
		
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
		if (!$this->config('markup.quote.keepSigns'))
		{
			return parent::blockQuote($Line);
		}
		
		if ($Line['text'][0] === '>' and preg_match('/^>(?:!)?[ ]?(.*)/', $Line['text'], $matches))
		{
			if (isset($Block['interrupted']))
			{
				$Block['element']['text'] []= '';
				
				unset($Block['interrupted']);
			}
			
			$Block['element']['text'] []= "&gt;" . $matches[1];
			
			return $Block;
		}
	}
}