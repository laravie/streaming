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
     * @param \React\EventLoop\LoopInterface $eventLoop
     */
    public function __construct(array $config, LoopInterface $eventLoop)
    {
        $url = \sprintf('tcp://%s:%d', $config['host'], $config['port']);

        $options = [
            'eventloop' => $eventLoop,
            'phpiredis' => $this->detectRedisExtension($config),
        ];

        $this->connection = new PredisClient($url, $options);
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
        $this->connection->connect(function (PredisClient $predis) use ($listener) {
            $this->onConnected($predis, $listener);
        });

        return $this;
    }

    /**
     * Disconnect.
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->connection->disconnect();
    }

    /**
     * Get connection eventloop.
     *
     * @return \React\EventLoop\LoopInterface
     */
    final public function getEventLoop(): LoopInterface
    {
        return $this->connection->getEventLoop();
    }

    /**
     * Handle on connected.
     *
     * @param  \Predis\Async\Client  $predis
     * @param  \Laravie\Streaming\Listener  $listener
     *
     * @return void
     */
    protected function onConnected(PredisClient $predis, Listener $listener): void
    {
        $listener->onConnected($predis);

        if (\method_exists($listener, 'withEventLoop')) {
            $listener->withEventLoop($predis->getEventLoop());
        }

        $predis->pubSubLoop(['psubscribe' => $listener->subscribedChannels()], [$listener, 'onEmitted']);

        $listener->onSubscribed($predis);
    }

    /**
     * Detect phpiredis extension and check configuration to verify whether we should use it.
     *
     * @param  array  $config
     *
     * @return bool
     */
    final protected function detectRedisExtension(array $config): bool
    {
        if (! \extension_loaded('phpiredis')) {
            return false;
        }

        return (bool) ($config['phpiredis'] ?? false);
    }
}
