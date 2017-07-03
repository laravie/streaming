<?php

namespace Laravie\Streaming\Tests;

use Laravie\Streaming\Client;
use Laravie\Streaming\Listener;
use Predis\Async\Client as PredisClient;

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
     *
     * @param  \Predis\Async\Client  $client
     *
     * @return mixed
     */
    public function onConnected($client)
    {
        $client->getEventLoop()->futureTick(function () {
            $this->redis->publish('topic:general', 'Hello world');
        });

        $this->assertTrue(true, 'Client connected!');
        $this->assertInstanceOf(PredisClient::class, $client);
    }

    /**
     * Trigger on subscribed listener.
     *
     * @param  \Predis\Async\Client  $client
     *
     * @return mixed
     */
    public function onSubscribed($client)
    {
        $this->assertTrue(true, 'Client subscribed!');
        $this->assertInstanceOf(PredisClient::class, $client);
    }

    /**
     * Trigger on emitted listener.
     *
     * @param object $event
     * @param object $pubsub
     *
     * @return mixed
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
     *
     * @return array
     */
    public function subscribedChannels()
    {
        return ['topic:*'];
    }
}
