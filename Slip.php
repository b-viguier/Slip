<?php

namespace bviguier\Slip;

/**
 * Non-blocking call if executed through a Slipper.
 */
function sleep(int $seconds): void
{
    if (\Fiber::getCurrent() !== null) {
        \Fiber::suspend($seconds);
    } else {
        \sleep($seconds);
    }
}

/**
 * Small wrapper class around a Fiber.
 */
class Slipper
{
    /** The Fiber to execute */
    private \Fiber $fiber;
    /** Time at which Fiber expects to be awaken */
    private int $timeout;

    public function __construct(callable $callable, mixed ...$args)
    {
        $this->fiber = new \Fiber($callable);
        /**
         * The Fiber is executed until its first interruption (or end),
         * returning the amount of time to sleep (or null).
         */
        $this->timeout = $this->fiber->start(...$args) ?? 0;
    }

    /**
     * Runs all internal Fibers in a concurrent way,
     * according to expected sleep time.
     */
    static public function run(Slipper ...$slippers): array
    {
        $results = [];
        $now = 0;

        /**
         * Iterates until all Slippers are terminated.
         */
        while ($slippers) {
            /**
             * Sorts Sleepers according to their timeout.
             * Shortest timeouts must be treated first!
             */
            uasort($slippers, static fn(Slipper $a, Slipper $b): int => $a->timeout - $b->timeout);

            /**
             * Pops the first element, and keep associated key to preserve order of returned values.
             */
            $slipperId = array_key_first($slippers);
            $currentSlipper = $slippers[$slipperId];
            unset($slippers[$slipperId]);

            /**
             * Retrieves the value returned by terminated Slippers.
             */
            if ($currentSlipper->fiber->isTerminated()) {
                $results[$slipperId] = $currentSlipper->fiber->getReturn();
                continue;
            }

            /**
             * Sleeps until the timeout expected by the current Slipper
             */
            if ($currentSlipper->timeout > $now) {
                \bviguier\Slip\sleep($sleepTime = $currentSlipper->timeout - $now);
                $now += $sleepTime; // Not accurate, but enough for the purpose of this proof of concept.
            }

            /**
             * Resumes the current Slippers, retrieves its new timeout
             * and queue it again for further treatment.
             */
            $duration = $currentSlipper->fiber->resume() ?? 0;
            $currentSlipper->timeout = $now + $duration;
            $slippers[$slipperId] = $currentSlipper;
        }

        /**
         * Ensures consistency between input Slippers and output values.
         */
        ksort($results);

        return $results;
    }
}
