<?php

namespace Laravie\Streaming\Tests;

use Laravie\Streaming\Client;
use Laravie\Streaming\Listener;

class ChatTest extends TestCase implements Listener
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        unset($this->client);
    }

    /** @test */
    public function test_it_can_listen_to_published_message()
    {
        $this->client = new Client([
            'host' => $this->getRedisHost(),
            'port' => $this->getRedisPort(),
        ]);

        $this->client->connect($this);
    }

    /**
     * Trigger on connected listener.
     *
     * @param  \Laravie\Streaming\Client  $client
     * @param  \Predis\Async\Client  $predis
     */
    public function onConnected($client, $predis)
    {
        $predis->getEventLoop()->futureTick(function () {
            $this->redis->publish('topic:general', 'Hello world');
        });

        $this->assertTrue(true, 'Client connected!');
        $this->assertInstanceOf('Laravie\Streaming\Client', $client);
        $this->assertInstanceOf('Predis\Async\Client', $predis);
    }

    /**
     * Trigger on subscribed listener.
     *
     * @param  \Laravie\Streaming\Client  $client
     * @param  \Predis\Async\Client  $predis
     *
     * @return void
     */
    public function onSubscribed($client, $predis)
    {
        $this->assertTrue(true, 'Client subscribed!');
        $this->assertInstanceOf('Laravie\Streaming\Client', $client);
        $this->assertInstanceOf('Predis\Async\Client', $predis);
    }

    /**
     * Trigger on emitted listener.
     *
     * Assert that `PUBLISH general "Hello world" was catched by the listener.
     */
    public function onEmitted($event, $pubsub): void
    {
        $this->assertEquals('pmessage', $event->kind);
        $this->assertEquals('topic:*', $event->pattern);
        $this->assertEquals('topic:general', $event->channel);
        $this->assertEquals('Hello world', $event->payload);
        $this->client->disconnect();
    }

    /**
     * List of subscribed channels.
     */
    public function subscribedChannels(): array
    {
        return ['topic:*'];
    }
}
