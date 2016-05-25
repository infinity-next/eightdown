<?php

namespace InfinityNext\Tests\Eightdown;

use InfinityNext\Eightdown\Eightdown;
use InfinityNext\Eightdown\Parsedown;
use InfinityNext\Eightdown\EightdownServiceProvider;
use GrahamCampbell\TestBench\AbstractPackageTestCase;

abstract class AbstractTestCase extends AbstractPackageTestCase
{
    protected function getEightdown(array $config = [])
    {
        return new Eightdown($config);
    }

    protected function getParsedown()
    {
        return new Parsedown;
    }

    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return EightdownServiceProvider::class;
    }
}
