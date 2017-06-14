<?php

namespace aMoniker\LedgerPrices\Command;

use aMoniker\LedgerPrices\Scraper\Controller;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class GetPricesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('prices-display')
            ->setDescription('Display the current prices without updating your price db')
            ->setHelp('TODO')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Getting prices.');

        $rates = [];
        foreach ((new Controller())->getRates() as $rate) {
            // format required for table output
            $rates[] = [$rate->from, $rate->to, $rate->rate];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['From', 'To', 'Rate'])
            ->setRows($rates);
        ;
        $table->render();
    }
}
