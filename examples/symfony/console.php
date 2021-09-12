<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new \bviguier\example\PingCommand());
$application->add(new \bviguier\example\PongCommand());
$application->add(new \bviguier\example\PingPongCommand());

$application->run();
