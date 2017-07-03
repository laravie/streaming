<?php

namespace Laravie\Streaming\Tests;

use Predis\Client;
use PHPUnit\Framework\TestCase as PHPUnit;

class TestCase extends PHPUnit
{
    /**
     * Predis Client.
     *
     * @var \Predis\Client
     */
    protected $redis;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host' => $this->getRedisHost(),
            'port' => $this->getRedisPort(),
        ]);
    }

    /**
     * Teardown the test environment.
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->redis);
    }

    /**
     * Get redis host.
     *
     * @return string
     */
    protected function getRedisHost()
    {
        return isset($_ENV['REDIS_HOST']) ? $_ENV['REDIS_HOST'] : '127.0.0.1';
    }

    /**
     * Get redis port.
     *
     * @return int
     */
    protected function getRedisPort()
    {
        return isset($_ENV['REDIS_PORT']) ? (int) $_ENV['REDIS_PORT'] : 6379;
    }
}
