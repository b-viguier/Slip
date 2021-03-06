<?php

namespace bviguier\example;

use bviguier\Slip;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PongCommand extends Command
{
    protected static $defaultName = 'pong';

    protected function configure(): void
    {
        $this
            ->setDescription('the pong command.')
            ->addArgument('count', InputArgument::REQUIRED, "Number of pong to display");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $input->getArgument('count');

        Slip\sleep(1);
        while ($count--) {
            $output->writeln("Pong");
            Slip\sleep(2);
        }

        $output->writeln("Finished Pong");

        return Command::SUCCESS;
    }
}
