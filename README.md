Redis Async Streaming for PHP
==============

The project allows you as a developer to listen to Redis pubsub using async instead of blocking I/O using PHP. This is done by utilizing `predis/predis-async` under the hood.

[![tests](https://github.com/laravie/streaming/workflows/tests/badge.svg?branch=master)](https://github.com/laravie/streaming/actions?query=workflow%3Atests+branch%3Amaster)
[![Latest Stable Version](https://poser.pugx.org/laravie/streaming/v/stable)](https://packagist.org/packages/laravie/streaming)
[![Total Downloads](https://poser.pugx.org/laravie/streaming/downloads)](https://packagist.org/packages/laravie/streaming)
[![Latest Unstable Version](https://poser.pugx.org/laravie/streaming/v/unstable)](https://packagist.org/packages/laravie/streaming)
[![License](https://poser.pugx.org/laravie/streaming/license)](https://packagist.org/packages/laravie/streaming)
[![Coverage Status](https://coveralls.io/repos/github/laravie/streaming/badge.svg?branch=master)](https://coveralls.io/github/laravie/streaming?branch=master)

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "laravie/streaming": "^3.0"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

    composer require "laravie/streaming=^3.0"


### Example

```php
<?php

$eventLoop = React\EventLoop\Factory::create();

$chat = new class implements Laravie\Streaming\Listener {
    /**
     * @return array
     */
    public function subscribedChannels(): array {
        return ['topic:*'];
    }
    
    /**
     * @param  \Predis\Async\Client  $redis  
     * @return void
     */
    public function onConnected($redis) {
        echo "Connected to redis!";
    }

    /**
     * @param  \Predis\Async\Client  $redis  
     * @return void
     */
    public function onSubscribed($redis) {
        echo "Subscribed to channel `topic:*`!";
    }
    
    /**
     * Trigger on emitted listener.
     *
     * @param  object  $event
     * @param  object  $pubsub
     *
     * @return void
     */
    public function onEmitted($event, $pubsub) {
        // PUBLISH topic:laravel "Hello world"
        
        # DESCRIBE $event
        #
        # {
        #   "kind": "pmessage",
        #   "pattern": "topic:*",
        #   "channel": "topic:laravel",
        #   "payload": "Hello world"
        # }
    }
}

$client = new Laravie\Streaming\Client(
    ['host' => '127.0.0.1', 'port' => 6379], $eventLoop
);

$client->connect($chat);

$eventLoop->run();
```
