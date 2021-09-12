<?php

require __DIR__.'/../Slip.php';

use bviguier\Slip;

function ping(int $count): string
{
    while ($count--) {
        echo "Ping\n";
        Slip\sleep(2);
    }

    return "Finished Ping\n";
}

function pong(int $count): string
{
    Slip\sleep(1);
    while ($count--) {
        echo "Pong\n";
        Slip\sleep(2);
    }

    return "Finished Pong\n";
}

const LIMIT = 2;

echo "Synchronous Execution\n==================\n";
echo ping(LIMIT);
echo pong(LIMIT);

echo "\nAsynchronous Execution\n==================\n";
echo join(
    Slip\Slipper::run(
        new Slip\Slipper(ping(...), LIMIT),
        new Slip\Slipper(pong(...), LIMIT),
    )
);
