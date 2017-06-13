<?php

namespace aMoniker\LedgerPrices\Scraper;

use aMoniker\LedgerPrices\Config;

class Controller {
    protected $config;
    protected $scraper_namespace = 'aMoniker\\LedgerPrices\\Scraper\\';

    public function __construct()
    {
        // TODO - use symfony config
        $this->config = new Config();
    }

    public function getConfiguredScrapers()
    {
        return $this->config->get('scrapers');
    }

    public function getScraper($classname)
    {
        return $this->instantiateScraper($classname);
    }

    protected function instantiateScraper($classname)
    {
        $namespaced_path = "{$this->scraper_namespace}$classname";
        return class_exists($namespaced_path) ? new $namespaced_path : null;
    }
}
