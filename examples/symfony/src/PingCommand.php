<?php

namespace bviguier\example;

use bviguier\Slip;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PingCommand extends Command
{
    protected static $defaultName = 'ping';

    protected function configure(): void
    {
        $this
            ->setDescription('the ping command.')
            ->addArgument('count', InputArgument::REQUIRED, "Number of ping to display");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $input->getArgument('count');

        while ($count--) {
            $output->write("Ping\n");
            Slip\sleep(2);
        }

        $output->write("Finished Ping\n");

        return Command::SUCCESS;
    }
}
