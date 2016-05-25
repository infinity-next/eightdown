<?php

namespace InfinityNext\Tests\Eightdown\Parsedown\Markup;

use InfinityNext\Tests\Eightdown\AbstractTestCase;

class BaseSyntaxTest extends AbstractTestCase
{
    /**
     * Parser used for simple tests.
     *
     * @var \InfinityNext\Eightdown\Parsedown
     */
    protected $parser;

    /**
     * Compounds a string with logical linebreaks.
     *
     * @param  string  $text  Text to iterate.
     * @param  string  $expected  Text to expect from each iteration.
     * @param  integer  $n  Iterations to build.
     */
    protected function assertManyLines($text, $expected = null, $n = 10)
    {
        if (is_null($expected))
        {
            $expected = $text;
        }

        $this->assertEquals("<p>$expected</p>", $this->parser->text($text));

        // Multple lines, unbroken.
        for ($x = 1; $x <= $n; ++$x)
        {
            $givenText = $text;
            $expectedText = "<p>" . $expected;
            for ($y = 1; $y < $x; ++$y)
            {
                $givenText .= "\n{$text}";
                $expectedText .= "\n{$expected}";
            }
            $expectedText .= "</p>";

            $this->assertEquals($expectedText, $this->parser->text($givenText));
        }

        // Multple lines, broken.
        for ($x = 1; $x <= $n; ++$x)
        {
            $givenText = $text;
            $expectedText = "<p>" . $expected;
            for ($y = 1; $y < $x; ++$y)
            {
                // Every 3rd line is not broken.
                if ($y % 3 > 0)
                {
                    $givenText .= "  \n{$text}";
                    $expectedText .= "<br />\n{$expected}";
                }
                else
                {
                    $givenText .= "\n{$text}";
                    $expectedText .= "\n{$expected}";
                }
            }
            $expectedText .= "</p>";

            $this->assertEquals($expectedText, $this->parser->text($givenText));
        }
    }

    /**
     * Pulls a parser instance for testing.
     */
    public function setUp()
    {
        $this->parser = $this->getParsedown();
    }

    public function testSimpleText()
    {
        // One line, no formatting.
        $simpleText = "The quick brown fox jumps over the lazy dog.";
        $this->assertEquals("<p>{$simpleText}</p>", $this->parser->text("\n\n\n\n\n\n{$simpleText}\n\n\n\n\n\n"));
        $this->assertEquals("<p>{$simpleText}</p>", $this->parser->text($simpleText));
        $this->assertManyLines($simpleText);
    }

    public function testLipsum()
    {
        $input  = file_get_contents(__DIR__."/Cases/LipsumInput.txt");
        $output = file_get_contents(__DIR__."/Cases/LipsumOutput.txt");

        $this->assertEquals(trim($output), $this->parser->text($input));
    }

    public function testItalics()
    {
        $expectedText = "The <em>quick</em> brown fox jumps over the lazy dog.";

        // One line with italics.
        $sampleText = "The _quick_ brown fox jumps over the lazy dog.";
        $this->assertEquals("<p>{$expectedText}</p>", $this->parser->text($sampleText));
        $this->assertManyLines($sampleText, $expectedText);

        $sampleText = "The *quick* brown fox jumps over the lazy dog.";
        $this->assertEquals("<p>{$expectedText}</p>", $this->parser->text($sampleText));
        $this->assertManyLines($sampleText, $expectedText);
    }
}
