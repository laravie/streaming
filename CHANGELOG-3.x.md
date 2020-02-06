# Changelog for 3.x

This changelog references the relevant changes (bug and security fixes) done to `laravie/streaming`.

## 3.1.0

Released: 2020-02-06

### Changes

* Bump minimum PHP to 7.2+.
* Support `laravie/predis-async` `0.4`+.

## 3.0.1

Released: 2019-04-15

### Changes

* Allow to a listener on client with existing redis connection.

## 3.0.0

Released: 2019-03-26

### Changes

* Require `React\EventLoop\LoopInterface` as 2nd parameter for `Laravie\Streaming\Client`.
* Require developer to manually run `React\EventLoop\LoopInterface::run()` after executing `Laravie\Streaming\Client::connect()`.
* `Laravie\Streaming\Listener` may have the option to receive instance of `React\EventLoop\LoopInterface` via `Listener::withEventLoop()` method.
