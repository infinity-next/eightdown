<?php namespace InfinityNext\Eightdown\Traits;

trait ParsedownSpoilers {
	
	protected function enableMarkupSpoiler()
	{
		return $this->addInlineType(">", 'Spoiler')
			->extendInline('Spoiler', function ($Excerpt)
			{
				if (!isset($Excerpt['text'][1]))
				{
					return;
				}
				
				if ($Excerpt['text'][0] === '>' && $Excerpt['text'][1] === '!' && preg_match('/^>!(?=\S)(.+?)(?<=\S)!</', $Excerpt['text'], $matches))
				{
					return [
						'extent' => strlen($matches[0]),
						'element' => [
							'name' => 'span',
							'text' => $matches[1],
							'handler' => 'line',
							'attributes' => [
								'class' => "spoiler",
							],
						],
					];
				}
			});
	}
	
}