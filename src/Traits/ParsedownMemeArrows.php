<?php

namespace InfinityNext\Eightdown\Traits;

trait ParsedownMemeArrows
{
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

        if (mb_ereg('(^>+[ ]?(.*)$)', $Line['text'], $matches))
        {
            $text = str_replace(">", "&gt;", $matches[0]);
            $Block = array(
                'element' => array(
                    'name' => 'blockquote',
                    'handler' => 'lines',
                    'text' => [$text],
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

        if (mb_ereg('(^>+[ ]?(.*)$)', $Line['text'], $matches))
        {
            if (isset($Block['interrupted']))
            {
                $Block['element']['text'][] = '';

                unset($Block['interrupted']);
            }

            $Block['element']['text'][] = "&gt;" . $matches[1];
            return $Block;
        }
    }
}
