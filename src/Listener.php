<?php

namespace Laravie\Streaming;

interface Listener
{
    /**
     * Trigger on connected listener.
     *
     * @return mixed
     */
    public function onConnected();

    /**
     * Trigger on subscribed listener.
     *
     * @return mixed
     */
    public function onSubscribed();

    /**
     * Trigger on emitted listener.
     *
     * @param object $event
     * @param object $pubsub
     *
     * @return mixed
     */
    public function onEmitted($event, $pubsub);

    /**
     * List of subscribed channels.
     *
     * @return array
     */
    public function subscribedChannels();
}
