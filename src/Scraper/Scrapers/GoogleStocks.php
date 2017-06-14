<?php

namespace aMoniker\LedgerPrices\Scraper\Scrapers;

use aMoniker\LedgerPrices\Scraper\Scraper;
use aMoniker\LedgerPrices\Exception\ScraperException;
use GuzzleHttp\Exception\RequestException;

/**
 * Uses the google API to obtain stock quotes
 *
 * Currently only handles USD
 */
class GoogleStocks extends Scraper {

    protected $name = 'Google Stocks';
    protected $base_uri = 'https://www.google.com/finance/info?q=';

    public function uri()
    {
        return "{$this->base_uri}{$this->from_symbol}";
    }

    public function scrape() {
        try {
            $body = substr($this->response->getBody(), 3);

            $json = json_decode($body, true);
            if (empty($json)) {
                throw new ScraperException('No valid JSON in response');
            }
            if (!isset($json[0])) {
                throw new ScraperException("No valid {$this->to_symbol} rate in JSON response");
            }
            $r = $json[0];
            $rate = (float) ($r['l_cur'] ?? 0);
            if ($rate === null || $rate <= 0) {
                throw new ScraperException("Missing rate for {$this->to_symbol} in JSON response");
            }
        } catch (RequestException $e) {
            throw new ScraperException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->formatRate($rate);
    }
}
