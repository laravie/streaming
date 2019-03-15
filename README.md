Redis Async Streaming for PHP
==============

The project allows you as a developer to listen to Redis pubsub using async instead of blocking I/O using PHP. This is done by utilizing `predis/predis-async` under the hood.

[![Build Status](https://travis-ci.org/laravie/streaming.svg?branch=master)](https://travis-ci.org/laravie/streaming)
[![Latest Stable Version](https://poser.pugx.org/laravie/streaming/v/stable)](https://packagist.org/packages/laravie/streaming)
[![Total Downloads](https://poser.pugx.org/laravie/streaming/downloads)](https://packagist.org/packages/laravie/streaming)
[![Latest Unstable Version](https://poser.pugx.org/laravie/streaming/v/unstable)](https://packagist.org/packages/laravie/streaming)
[![License](https://poser.pugx.org/laravie/streaming/license)](https://packagist.org/packages/laravie/streaming)

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

use Laravie\Streaming\Client;
use Laravie\Streaming\Listener;
use Predis\Async\Client as Predis;

$chat = new class implements Listener {
    public function subscribedChannels(): array {
        return ['topic:*'];
    }

    public function onConnected(Client $client, Predis $redis): void {
        echo "Connected to redis!";
    }

    public function onSubscribed(Client $client, Predis $redis): void {
        echo "Subscribed to channel `topic:*`!";
    }

    public function onEmitted($event, $pubsub): void {
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

$client = new Client(['host' => '127.0.0.1', 'port' => 6379]);
$client->connect($chat);
```
