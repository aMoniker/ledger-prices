<?php

namespace aMoniker\LedgerPrices\Scraper;

use aMoniker\LedgerPrices\Config;

class Controller {
    protected $config;
    protected $scraper_namespace = 'aMoniker\\LedgerPrices\\Scraper\\Scrapers\\';

    public function __construct()
    {
        // TODO - use symfony config
        $this->config = new Config();
    }

    public function getRates()
    {
        $rates = [];
        $scrapers = $this->config->get('scrapers');

        foreach ($scrapers as $class => $config) {
            $scraper = $this->instantiateScraper($class);
            if ($scraper === null) {
                // $output->writeln("ERROR: Scraper not found: $scraper_class");
                // TODO - log this
                continue;
            }

            $conversions = $config['conversions'] ?? [];

            foreach ($conversions as $from => $to) {
                $rate = $scraper->process($from, $to);
                if (empty($rate)) {
                    // $output->writeLn("Price for $symbol could not be found.");
                    // TODO - log this
                    continue;
                }
                $rates[$from] = $rate;
            }
        }

        return $rates;
    }

    protected function instantiateScraper($classname)
    {
        $namespaced_path = "{$this->scraper_namespace}$classname";
        return class_exists($namespaced_path) ? new $namespaced_path : null;
    }
}
