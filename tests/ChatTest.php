<?php

namespace Laravie\Streaming\Tests;

use Laravie\Streaming\Client;
use Laravie\Streaming\Listener;
use Predis\Async\Client as Predis;

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
     * @param  \Predis\Async\Client  $predis
     */
    public function onConnected($predis)
    {
        $predis->getEventLoop()->futureTick(function () {
            $this->redis->publish('topic:general', 'Hello world');
        });

        $this->assertTrue(true, 'Client connected!');
        $this->assertInstanceOf(Predis::class, $predis);
    }

    /**
     * Trigger on subscribed listener.
     *
     * @param  \Predis\Async\Client  $predis
     *
     * @return void
     */
    public function onSubscribed($predis)
    {
        $this->assertTrue(true, 'Client subscribed!');
        $this->assertInstanceOf(Predis::class, $predis);
    }

    /**
     * Trigger on emitted listener.
     *
     * Assert that `PUBLISH general "Hello world" was catched by the listener.
     */
    public function onEmitted($event, $pubsub)
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
