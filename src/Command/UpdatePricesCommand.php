<?php

namespace aMoniker\LedgerPrices\Command;

use aMoniker\LedgerPrices\Scraper\Controller;
use aMoniker\LedgerPrices\PriceDB;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdatePricesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('prices-update')
            ->setDescription('Updates the prices of your pricedb file')
            ->setHelp('TODO')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Updating prices.');

        $rates = (new Controller())->getRates();
        $update_count = (new PriceDB())->updateMultiple($rates);
        $rate_count = count($rates);

        $output->writeln("Processed $rate_count conversions. Made $update_count updates.");
    }
}
