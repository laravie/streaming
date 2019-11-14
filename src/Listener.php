<?php

namespace Laravie\Streaming;

interface Listener
{
    /**
     * Trigger on connected listener.
     *
     * @param  \Predis\Async\Client  $predis
     *
     * @return void
     */
    public function onConnected($predis);

    /**
     * Trigger on subscribed listener.
     *
     * @param  \Predis\Async\Client  $predis
     *
     * @return void
     */
    public function onSubscribed($predis);

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
     */
    public function subscribedChannels(): array;
}
