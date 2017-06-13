<?php

namespace aMoniker\LedgerPrices\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdatePricesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('update-prices')
            ->setDescription('Updates the prices of your pricedb file')
            ->setHelp('TODO')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Welcome',
            'Ready to scrape prices...',
        ]);
    }
}
