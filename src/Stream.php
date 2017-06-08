<?php

namespace Laravie\Streaming;

use Predis\Async\Client;
use React\EventLoop\Factory as EventLoop;

class Stream
{
    /**
     * Redis Async Client.
     *
     * @var \Predis\Async\Client
     */
    protected $client;
    /**
     * Construct a new streaming service.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $connection = sprintf('tcp://%s:%d', $config['host'], $config['port']);

        $this->client = new Client($connection, [
            'eventloop' => EventLoop::create(),
        ]);
    }
    /**
     * Connect to streaming service.
     *
     * @param \Laravie\Streaming\Listener $listener
     */
    public function connect(Listener $listener)
    {
        $this->client->connect(function (Client $client) use ($listener) {
            $listener->onConnected();
            $client->pubSubLoop(['psubscribe' => $listener->subscribedChannels()], [$listener, 'onEmitted']);
            $listener->onSubscribed();
        });

        $this->client->getEventLoop()->run();
    }
}
