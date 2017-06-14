<?php

namespace aMoniker\LedgerPrices\Scraper\Scrapers;

use aMoniker\LedgerPrices\Scraper\Scraper;
use aMoniker\LedgerPrices\Exception\ScraperException;
use GuzzleHttp\Exception\RequestException;

/**
 * Uses the API from goldprice.org to obtain the latest Gold and Silver conversion rates
 *
 * "XAU" represents the price for an ounce of gold.
 * "XAG" represents the price for an ounce of silver.
 *
 * Regular currency codes are supported (USD/EUR/GBP/etc).
 */
class GoldPrice extends Scraper {

    protected $name = 'GoldPrice';
    protected $base_uri = 'http://data-asg.goldprice.org/GetData/<to>-<from>/1';

    public function uri()
    {
        return str_replace(
            ['<to>', '<from>'], [$this->to_symbol, $this->from_symbol], $this->base_uri
        );
    }

    public function scrape() {
        try {
            $json = json_decode($this->response->getBody(), true);
            if (empty($json)) {
                throw new ScraperException('No valid JSON in response');
            }
            if (!isset($json[0])) {
                throw new ScraperException("No valid {$this->to_symbol} rate in JSON response");
            }
            $r = explode(',', $json[0]);
            $rate = (float) ($r[1] ?? 0);
            if ($rate === null || $rate <= 0) {
                throw new ScraperException("Missing rate for {$this->to_symbol} in JSON response");
            }
        } catch (RequestException $e) {
            throw new ScraperException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->formatRate($rate);
    }
}
