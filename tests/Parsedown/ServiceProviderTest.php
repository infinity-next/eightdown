<?php

namespace InfinityNext\Tests\Eightdown\Parsedown;

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use InfinityNext\Eightdown\Parsedown;
use InfinityNext\Tests\Eightdown\AbstractTestCase;

class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    /**
     * Test if Parsedown is injectable.
     *
     * @return void
     */
    public function testParsedownIsInjectable()
    {
        $this->assertIsInjectable(Parsedown::class);
    }
}
