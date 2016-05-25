<?php

namespace InfinityNext\Tests\Eightdown\Eightdown\Markup;

use InfinityNext\Tests\Eightdown\AbstractTestCase;

class DirectionalTest extends AbstractTestCase
{
    /**
     * Parser used for simple tests.
     *
     * @var \InfinityNext\Eightdown\Parsedown
     */
    protected $parser;

    /**
     * Pulls a parser instance for testing.
     */
    public function setUp()
    {
        $this->parser = $this->getEightdown([
            'general' => [
                'keepLineBreaks' => true,
                'parseHTML'      => false,
                'parseURL'       => true,
            ],
        ]);
    }

    public function testArabicFile()
    {
        $input  = file_get_contents(__DIR__."/Cases/ArabicInput.txt");
        $output = file_get_contents(__DIR__."/Cases/ArabicOutput.txt");

        $this->assertEquals(trim($output), $this->parser->parse($input));
    }
}
