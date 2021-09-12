<?php

namespace bviguier\Slip;

function sleep(int $seconds): void
{
    if (\Fiber::getCurrent() !== null) {
        \Fiber::suspend($seconds);
    } else {
        \sleep($seconds);
    }
}

class Slipper
{
    private \Fiber $fiber;
    private int $timeout;

    public function __construct(callable $callable, mixed ...$args)
    {
        $this->fiber = new \Fiber($callable);
        $this->timeout = $this->fiber->start(...$args) ?? 0;
    }


    static public function run(Slipper ...$slips): array
    {
        $results = [];
        $now = 0;
        while ($slips) {
            uasort($slips, static fn(Slipper $a, Slipper $b): int => $a->timeout - $b->timeout);

            $slipId = array_key_first($slips);
            $currentSlip = $slips[$slipId];
            unset($slips[$slipId]);

            if ($currentSlip->fiber->isTerminated()) {
                $results[$slipId] = $currentSlip->fiber->getReturn();
                continue;
            }

            if ($currentSlip->timeout > $now) {
                \bviguier\Slip\sleep($sleepTime = $currentSlip->timeout - $now);
                $now += $sleepTime;
            }

            $duration = $currentSlip->fiber->resume() ?? 0;
            $currentSlip->timeout = $now + $duration;
            $slips[$slipId] = $currentSlip;
        }

        ksort($results);

        return $results;
    }
}