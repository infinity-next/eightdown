<?php

namespace InfinityNext\Tests\Eightdown\Eightdown;

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use InfinityNext\Eightdown\Eightdown;
use InfinityNext\Tests\Eightdown\AbstractTestCase;

class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    /**
     * Test if Parsedown is injectable.
     *
     * @return void
     */
    public function testEightdownIsInjectable()
    {
        $this->assertIsInjectable(Eightdown::class);
    }
}
