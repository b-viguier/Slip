<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

bviguier\Slip\Slipper::run(
    new bviguier\Slip\Slipper(runCommand(...), \bviguier\example\PingCommand::class),
    new bviguier\Slip\Slipper(runCommand(...), \bviguier\example\PongCommand::class),
);

function runCommand(string $className): void
{
    $application = new Application();
    $application->add($command = new $className());
    $application->setDefaultCommand($command->getName(), true);
    $application->run();
}
