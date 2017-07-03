Redis Async Streaming for PHP
==============

[![Latest Stable Version](https://poser.pugx.org/laravie/streaming/v/stable)](https://packagist.org/packages/laravie/streaming)
[![Total Downloads](https://poser.pugx.org/laravie/streaming/downloads)](https://packagist.org/packages/laravie/streaming)
[![Latest Unstable Version](https://poser.pugx.org/laravie/streaming/v/unstable)](https://packagist.org/packages/laravie/streaming)
[![Build Status](https://travis-ci.org/laravie/streaming.svg?branch=master)](https://travis-ci.org/laravie/streaming)
[![License](https://poser.pugx.org/laravie/streaming/license)](https://packagist.org/packages/laravie/streaming)

The project allows you as a developer to listen to Redis pubsub using async instead of blocking I/O using PHP. This is done by utilizing `predis/predis-async` under the hood.

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "laravie/streaming": "~1.0"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

    composer require "laravie/streaming=~1.0"
