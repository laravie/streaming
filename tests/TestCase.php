<?php

namespace Laravie\Streaming\Tests;

use PHPUnit\Framework\TestCase as PHPUnit;
use Predis\Client;

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
     */
    protected function setUp(): void
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host' => $this->getRedisHost(),
            'port' => $this->getRedisPort(),
        ]);
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
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
        return $_ENV['REDIS_HOST'] ?? '127.0.0.1';
    }

    /**
     * Get redis port.
     *
     * @return int
     */
    protected function getRedisPort()
    {
        return (int) ($_ENV['REDIS_PORT'] ?? 6379);
    }
}
