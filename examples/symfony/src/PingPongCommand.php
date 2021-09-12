<?php

namespace bviguier\example;

use bviguier\Slip;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PingPongCommand extends Command
{
    protected static $defaultName = 'ping-pong';

    protected function configure(): void
    {
        $this
            ->setDescription('the ping-pong command.')
            ->addArgument('count', InputArgument::REQUIRED, "Number of ping-pong to display");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $input->getArgument('count');

        $results = Slip\Slipper::run(
            new Slip\Slipper($this->executeChildCommand(...), 'ping', $count, $output),
            new Slip\Slipper($this->executeChildCommand(...), 'pong', $count, $output),
        );

        return max($results);
    }

    private function executeChildCommand(string $name, int $count, OutputInterface $output): int
    {
        $command = $this->getApplication()->find($name);

        return $command->run(
            new ArrayInput(['count' => $count]),
            $output
        );
    }
}
