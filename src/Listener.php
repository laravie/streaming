<?php

namespace Laravie\Streaming;

interface Listener
{
    /**
     * Trigger on connected listener.
     *
     * @param  \Predis\Async\Client  $client
     *
     * @return void
     */
    public function onConnected($client): void;

    /**
     * Trigger on subscribed listener.
     *
     * @param  \Predis\Async\Client  $client
     *
     * @return void
     */
    public function onSubscribed($client): void;

    /**
     * Trigger on emitted listener.
     *
     * @param object $event
     * @param object $pubsub
     *
     * @return void
     */
    public function onEmitted($event, $pubsub): void;

    /**
     * List of subscribed channels.
     *
     * @return array
     */
    public function subscribedChannels(): array;
}
