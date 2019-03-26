# Changelog for 3.x

This changelog references the relevant changes (bug and security fixes) done to `laravie/streaming`.

## 3.0.0

### Changes

* Require `React\EventLoop\LoopInterface` as 2nd parameter for `Laravie\Streaming\Client`.
* Require developer to manually run `React\EventLoop\LoopInterface::run()` after executing `Laravie\Streaming\Client::connect()`.
* `Laravie\Streaming\Listener` may have the option to receive instance of `React\EventLoop\LoopInterface` via `Listener::withEventLoop()` method.
