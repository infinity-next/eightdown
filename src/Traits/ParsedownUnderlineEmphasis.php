<?php namespace InfinityNext\Eightdown\Traits;

trait ParsedownUnderlineEmphasis {

	protected function enableMarkupUnderlineEmphasis()
        {
                return $this->addInlineType("_", 'UnderlineEmphasis')
                        ->addInlineType("*", 'UnderlineEmphasis')
			->extendInline('UnderlineEmphasis', function ($Excerpt)
                        {
                            if ( ! isset($Excerpt['text'][1]))
                            {
                                return;
                            }

                            $marker = $Excerpt['text'][0];

                            if (substr($Excerpt['text'], 0, 2) === "**" and preg_match($this->StrongRegex[$marker], $Excerpt['text'], $matches))
                            {
                                $emphasis = 'strong';
                            }
                            elseif (substr($Excerpt['text'], 0, 2) === "__" and preg_match($this->StrongRegex[$marker], $Excerpt['text'], $matches))
                            {
                                $emphasis = 'ins';
                            }
                            elseif (preg_match($this->EmRegex[$marker], $Excerpt['text'], $matches))
                            {
                                $emphasis = 'em';
                            }
                            else
                            {
                                return;
                            }

                            return array(
                                'extent' => strlen($matches[0]),
                                'element' => array(
                                    'name' => $emphasis,
                                    'handler' => 'line',
                                    'text' => $matches[1],
                                ),
                            );
			});
        }

}