<?php

namespace Laravie\Streaming;

use React\EventLoop\LoopInterface;
use Predis\Async\Client as PredisClient;
use React\EventLoop\Factory as EventLoop;

class Client
{
    /**
     * Redis Async Client connection.
     *
     * @var \Predis\Async\Client
     */
    protected $connection;

    /**
     * Construct a new streaming service.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $url = sprintf('tcp://%s:%d', $config['host'], $config['port']);
        $eventloop = $this->resolveEventLoop(isset($config['loop']) ? $config['loop'] : null);

        $this->connection = new PredisClient($url, compact('eventloop'));
    }

    /**
     * Connect to streaming service.
     *
     * @param \Laravie\Streaming\Listener $listener
     *
     * @return $this
     */
    public function connect(Listener $listener)
    {
        $this->connection->connect(function (PredisClient $client) use ($listener) {
            $this->onConnected($client, $listener);
        });

        $this->getEventLoop()->run();

        return $this;
    }

    /**
     * Disconnect.
     *
     * @return void
     */
    public function disconnect()
    {
        $this->connection->disconnect();
    }

    /**
     * Get connection eventloop.
     *
     * @return \React\EventLoop\LoopInterface
     */
    protected function getEventLoop()
    {
        return $this->connection->getEventLoop();
    }

    /**
     * Handle on connected.
     *
     * @param  \Predis\Async\Client  $client
     * @param  \Laravie\Streaming\Listener  $listener
     *
     * @return void
     */
    protected function onConnected(PredisClient $client, Listener $listener)
    {
        $listener->onConnected($client);

        $client->pubSubLoop(['psubscribe' => $listener->subscribedChannels()], [$listener, 'onEmitted']);

        $listener->onSubscribed($client);
    }

    /**
     * Resolve event loop implementation.
     *
     * @param  \React\EventLoop\LoopInterface|null $loop
     *
     * @return \React\EventLoop\LoopInterface
     */
    protected function resolveEventLoop(LoopInterface $loop = null)
    {
        if (is_null($loop)) {
            $loop = EventLoop::create();
        }

        return $loop;
    }
}
