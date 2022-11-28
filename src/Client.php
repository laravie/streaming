<?php

namespace Laravie\Streaming;

use Predis\Async\Client as PredisClient;
use React\EventLoop\Factory as EventLoop;
use React\EventLoop\LoopInterface;

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
     */
    public function __construct(array $config, LoopInterface $eventLoop)
    {
        $url = sprintf('tcp://%s:%d', $config['host'], $config['port']);

        $options = [
            'eventloop' => $eventLoop,
            'phpiredis' => $this->detectRedisExtension($config),
        ];

        $this->connection = new PredisClient($url, $options);
    }

    /**
     * Connect to streaming service.
     *
     * @param  \Laravie\Streaming\Listener  $listener
     * @return $this
     */
    public function connect(Listener $listener)
    {
        if (! $this->connection->isConnected()) {
            $this->connection->connect(function (PredisClient $predis) use ($listener) {
                $this->onConnected($predis, $listener);
            });
        } else {
            $this->onConnected($this->connection, $listener);
        }

        return $this;
    }

    /**
     * Disconnect.
     */
    public function disconnect(): void
    {
        $this->connection->disconnect();
    }

    /**
     * Get connection eventloop.
     */
    final public function getEventLoop(): LoopInterface
    {
        return $this->connection->getEventLoop();
    }

    /**
     * Handle on connected.
     *
     * @param  \Laravie\Streaming\Listener  $listener
     */
    protected function onConnected(PredisClient $predis, Listener $listener): void
    {
        if (method_exists($listener, 'withEventLoop')) {
            $listener->withEventLoop($predis->getEventLoop());
        }

        $listener->onConnected($predis);

        $predis->pubSubLoop(['psubscribe' => $listener->subscribedChannels()], [$listener, 'onEmitted']);

        $listener->onSubscribed($predis);
    }

    /**
     * Detect phpiredis extension and check configuration to verify whether we should use it.
     */
    final protected function detectRedisExtension(array $config): bool
    {
        if (! \extension_loaded('phpiredis')) {
            return false;
        }

        return (bool) ($config['phpiredis'] ?? false);
    }
}
