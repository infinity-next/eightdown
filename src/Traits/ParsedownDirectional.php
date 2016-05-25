<?php

namespace InfinityNext\Eightdown\Traits;

trait ParsedownDirectional
{
    /**
     * Hebrew and Arabic characters.
     *
     * @var string
     */
    protected $RtlRegex = "\u{0600}-\u{06ff}|\u{0750}-\u{077f}|\u{fb50}-\u{fc3f}|\u{fe70}-\u{fefc}";

    /**
     * Tally of left-to-right characters.
     *
     * Does not include punctuation or currency symbols, only human language.
     *
     * @var integer
     */
    protected $textLtr = 0;

    /**
     * Hebrew and Arabic characters.
     *
     * @var integer
     */
    protected $textRtl = 0;

    /**
     * The determined post direction.
     *
     * In instances with inadequate or obtuse characters (like ">>5555"), it
     * will remain null and set to neither left nor right. In this case, the
     * post should not set a direction and follow the flow of the page.
     *
     * @var string|null
     */
    protected $textDir = null;

    /**
     * Is this parser's last processed text RTL?
     *
     * @return boolean true
     */
    public function isRtl()
    {
        return $this->textDir === "rtl";
    }

    /**
     * Is this parser's last processed text LTR?
     *
     * @return boolean true
     */
    public function isLtr()
    {
        return $this->textDir === "ltr";
    }

    public function text($text)
    {
        $this->textRtl = $this->getRtlStrLen($text);
        $this->textLtr = $this->getLtrStrLen($text);

        if ($this->textRtl > 0 && $this->textRtl > $this->textLtr)
        {
            $this->textDir = "rtl";
        }
        else if ($this->textLtr > 0 && $this->textLtr > $this->textRtl)
        {
            $this->textDir = "ltr";
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

        if ($this->breaksEnabled)
        {
            $parts = preg_split('/[ ]*\n/', $text);
        }
        else
        {
            $parts = preg_split('/(?:[ ][ ]+|[ ]*\\\\)\n/', $text);
        }

        foreach ($parts as $index => &$part)
        {
            if ($index > 0)
            {
                $newText .= "<br />\n";
            }

            // Remove trailing spaces.
            $part = preg_replace('/[ ]*\n/', "\n", $part);

            // Determine directon
            $ltr = $this->getLtrStrLen($part);
            $rtl = $this->getRtlStrLen($part);
            $dir = null;

            if ($this->isLtr() && $rtl > 0 && $rtl > $ltr)
            {
                $dir = "rtl";
            }
            else if ($this->isRtl() && $ltr > 0 && $ltr > $rtl)
            {
                $dir = "ltr";
            }

            if (!is_null($dir))
            {
                $newText .= "<div dir=\"{$dir}\">{$part}</div>";
            }
            else
            {
                $newText .= $part;
            }
        }

        return $newText;
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
