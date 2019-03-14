<?php

namespace Laravie\Streaming;

interface Listener
{
    /**
     * Trigger on connected listener.
     *
     * @param  \Laravie\Streaming\Client  $client
     * @param  \Predis\Async\Client  $predis
     *
     * @return void
     */
    public function onConnected($client, $predis);

    /**
     * Trigger on subscribed listener.
     *
     * @param  \Laravie\Streaming\Client  $client
     * @param  \Predis\Async\Client  $predis
     *
     * @return void
     */
    public function onSubscribed($client, $predis);

    /**
     * Trigger on emitted listener.
     *
     * @param object $event
     * @param object $pubsub
     *
     * @return void
     */
    public function onEmitted($event, $pubsub);

    /**
     * List of subscribed channels.
     *
     * @return array
     */
    public function subscribedChannels(): array;
}
