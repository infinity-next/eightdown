<?php

namespace InfinityNext\Eightdown\Traits;

trait ParsedownDirectional
{
    /**
     * Hebrew and Arabic characters.
     *
     * @var string
     */
    protected $RtlRegex = "\p{Arabic}|\p{Hebrew}|\p{Syriac}|\p{Thaana}";

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

    public function enableMarkupI18n()
    {
        mb_regex_encoding('UTF-8');
    }

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
            $parts = mb_split('[ ]*\n', $text);
        }
        else
        {
            $parts = mb_split('(?:[ ][ ]+|[ ]*\\\\)\n', $text);
        }

        foreach ($parts as $index => &$part)
        {
            if ($index > 0)
            {
                $newText .= "<br />\n";
            }

            // Remove trailing spaces.
            $part = mb_ereg_replace('/[ ]*\n/', "\n", $part);

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
        $text = mb_eregi_replace("(&(?:[a-z\d]+|#\d+|#x[a-f\d]+);)", "", $text);
        $text = mb_eregi_replace("{$this->RtlRegex}", "", $text);
        $text = mb_eregi_replace("\P{L}", "", $text);

        return mb_strlen($text);
    }

    protected function getRtlStrLen($text)
    {
        $rtl = "[^{$this->RtlRegex}]+";
        $text = mb_eregi_replace($rtl, "", $text);

        return mb_strlen($text);
    }
}
