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
            ->setName('update-prices')
            ->setDescription('Updates the prices of your pricedb file')
            ->setHelp('TODO')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Updating prices');

        $pricedb = new PriceDB();
        $controller = new Controller();
        $scrapers = $controller->getConfiguredScrapers();

        $symbol_count = 0;
        $update_count = 0;

        foreach ($scrapers as $scraper_class => $symbols) {
            $scraper = $controller->getScraper($scraper_class);
            if ($scraper === null) {
                $output->writeln("Scraper not found: $scraper_class");
                continue;
            }

            $name = $scraper->name();
            $output->writeln("Processing: {$name} symbols");

            foreach ($symbols as $symbol) {
                $symbol_count++;
                $rate = $scraper->process($symbol);
                if ($rate === null) {
                    $output->writeLn("Price for $symbol could not be found.");
                    continue;
                }

                $pricedb->update($symbol, $rate);
                $update_count++;
            }
        }

        $output->writeln("Finished processing $symbol_count symbols. $update_count updates made.");
    }
}
