<?php

namespace Laravie\Streaming\Tests;

use React\EventLoop\Factory;
use Laravie\Streaming\Client;
use Laravie\Streaming\Listener;
use Predis\Async\Client as Predis;
use React\EventLoop\LoopInterface;
use React\Stream\WritableResourceStream;

class StrictTypeChatTest extends TestCase implements Listener
{
    protected $client;
    protected $writableStream;

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        unset($this->client, $this->writableStream);
    }

    /** @test */
    public function test_it_can_listen_to_published_message()
    {
        $eventLoop = Factory::create();

        $this->client = new Client([
            'host' => $this->getRedisHost(),
            'port' => $this->getRedisPort(),
        ], $eventLoop);

        $this->client->connect($this);

        $eventLoop->run();
    }

    /**
     * Bind services with EventLoop.
     *
     * @param  \React\EventLoop\LoopInterface $eventLoop
     *
     * @return void
     */
    public function withEventLoop(LoopInterface $eventLoop): void
    {
        $this->writableStream = 'WritableResourceStream';
    }

    /**
     * Trigger on connected listener.
     *
     * @param  \Predis\Async\Client  $predis
     */
    public function onConnected($predis): void
    {
        $this->assertSame('WritableResourceStream', $this->writableStream);

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
    public function onSubscribed($predis): void
    {
        $this->assertTrue(true, 'Client subscribed!');
        $this->assertInstanceOf(Predis::class, $predis);
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
