<?php

namespace InfinityNext\Eightdown\Traits;

trait ParsedownUnderline
{
    protected function enableMarkupUnderline()
    {
        return $this->addInlineType("_", 'Underline')->extendInline('Underline', function ($Excerpt)
        {
            if (!isset($Excerpt['text'][1]))
            {
                return;
            }

            $marker = $Excerpt['text'][0];

            if (substr($Excerpt['text'], 0, 2) === "__" and preg_match($this->StrongRegex[$marker], $Excerpt['text'], $matches))
            {
                return [
                    'extent' => strlen($matches[0]),
                    'element' => [
                        'name' => 'ins',
                        'handler' => 'line',
                        'text' => $matches[1],
                    ],
                ];
            }
        });
    }
}
