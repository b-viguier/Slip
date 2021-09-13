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

echo "Synchronous Execution\n";
echo "=====================\n";
echo ping(2); // Ping
                    // Ping
                    // Finished Ping
echo pong(2); // Pong
                    // Pong
                    // Finished Pong

echo "\nAsynchronous Execution\n";
echo "========================\n";
echo join(
    Slip\Slipper::run(
        new Slip\Slipper(ping(...), 2),
        new Slip\Slipper(pong(...), 2),
    )
);
// Ping
                // Pong
// Ping
                // Pong
// Finished Ping
                // Finished Pong
