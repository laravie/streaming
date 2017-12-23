<?php

namespace Laravie\Streaming\Tests;

use Laravie\Streaming\Client;
use Laravie\Streaming\Listener;

class ChatTest extends TestCase implements Listener
{
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
     */
    public function onConnected($client): void
    {
        $client->getEventLoop()->futureTick(function () {
            $this->redis->publish('topic:general', 'Hello world');
        });

        $this->assertTrue(true, 'Client connected!');
        $this->assertInstanceOf('Predis\Async\Client', $client);
    }

    /**
     * Trigger on subscribed listener.
     */
    public function onSubscribed($client): void
    {
        $this->assertTrue(true, 'Client subscribed!');
        $this->assertInstanceOf('Predis\Async\Client', $client);
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
