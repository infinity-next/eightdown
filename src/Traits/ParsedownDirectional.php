<?php

namespace InfinityNext\Eightdown\Traits;

trait ParsedownDirectional
{
    public $RtlRegex = "\u{0600}-\u{06ff}|\u{0750}-\u{077f}|\u{fb50}-\u{fc3f}|\u{fe70}-\u{fefc}";
    public $textLtr = 0;
    public $textRtl = 0;
    public $textDir = "ltr";

    public function text($text)
    {
        $this->textRtl = $this->getRtlStrLen($text);
        $this->textLtr = $this->getLtrStrLen($text);

        if ($this->textRtl > 0 && $this->textRtl > $this->textLtr)
        {
            $this->textDir = "rtl";
        }

        return parent::text($text);
    }

    /**
     * Inject span tags for each line to indicate direction.
     *
     * @param  string  $text  Single line's worth of text.
     * @return string
     */
    protected function unmarkedText($text)
    {
        $newText = "";
        $matches = 0;

        if ($this->breaksEnabled)
        {
            $parts = preg_split('/[ ]*\n/', $text);
        }
        else
        {
            $parts = preg_split('/(?:[ ][ ]+|[ ]*\\\\)\n/', $text);
        }

        foreach ($parts as &$part)
        {
            // Remove trailing spaces.
            $part = preg_replace('/[ ]*\n/', "\n", $part);

            // Determine directon
            $ltr = $this->getLtrStrLen($part);
            $rtl = $this->getRtlStrLen($part);
            $dir = null;


            if ($this->textDir == "ltr" && $rtl > 0 && $rtl > $ltr)
            {
                $dir = "rtl";
            }
            else if ($this->textDir == "rtl" && $ltr > 0 && $ltr > $rtl)
            {
                $dir = "ltr";
            }

            if (!is_null($dir))
            {
                $part = "<span dir=\"{$dir}\">{$part}</span>";
            }
        }

        return implode($parts, "<br />\n");
    }

    protected function getLtrStrLen($text)
    {
        $ltr = "/(\p{L}[^{$this->RtlRegex}])+/i";
        $strLen = 0;

        if(preg_match_all($ltr, $text, $matches))
        {
            $matches = $this->filterBinary($matches[0]);
            $strLen += mb_strlen(implode($matches));
        }

        return $strLen;
    }

    protected function getRtlStrLen($text)
    {
        $rtl = "/([{$this->RtlRegex}])+/i";
        $strLen = 0;

        if(preg_match_all($rtl, $text, $matches))
        {
            $matches = $this->filterBinary($matches[0]);
            $strLen += mb_strlen(implode($matches));
        }

        return $strLen;
    }

    protected function filterBinary(array $items = [])
    {
        return array_filter($items, function ($item)
        {
            return preg_match('//u', $item);
        });
    }
}
