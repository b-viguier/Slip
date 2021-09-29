# Slip
Proof of concept of asynchronous programming with Php 8.1 and [Fiber](https://wiki.php.net/rfc/fibers)


**Slip** (pronounced like _"sleep"_ in english) is a _nano framework_ providing a non-blocking `sleep` function.

## How to install
Don't.
It's just a one file library for demonstration purpose only.
If you want to try it in your own project, just copy the [`Slip.php`](Slip.php) file.

## How to use it
⚠️ **Requires Php 8.1** ⚠️

**Slip** contains only 3 (documented) functions:
* wrap several functions in some `Slip\Slipper` objects
* use `Slip\sleep()` to pause your functions in a non-blocking way
* run your `Slip\Slipper` instances concurrently with `Slip\Slipper::run()`.

Some [examples](examples) are provided, using a Ping Pong analogy.

### [ping-pong.php](examples/ping-pong.php)
A `ping` and a `pong` functions are executed concurrently in a _vanilla_ Php program.

### [symfony/console.php](examples/symfony/console.php)
A small program using [Symfony Console Component](https://symfony.com/doc/current/components/console),
providing a [`ping`](examples/symfony/src/PingCommand.php) and a [`pong`](examples/symfony/src/PongCommand.php) commands.
First, run `composer install` to install dependencies, then you can execute commands individually (with
`php console.php <cmd> <count>`) or concurrently (with `php console.php ping-pong <count>`).

### [symfony/ping-pong.php](examples/symfony/ping-pong.php)
Previous example launches 1 application with several concurrent commands,
but this one launches several concurrent applications with a single command.
Be sure to install dependencies, then run it with `php ping-pong.php`.

## About the name `Slip`
It's a lame french joke, no offense…
We are **not** looking for a logo.
