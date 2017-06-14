<?php

namespace aMoniker\LedgerPrices\Scraper\Scrapers;

use aMoniker\LedgerPrices\Scraper\Scraper;
use aMoniker\LedgerPrices\Exception\ScraperException;
use GuzzleHttp\Exception\RequestException;

/**
 * Uses the API from CryptoCompare.com to obtain the latest cryptocurrency conversion rates.
 *
 * See https://www.cryptocompare.com/api/data/coinlist/ for a list of
 * available from/to symbols to convert. They also support USD/EUR/GBP et al.
 */
class CryptoCompare extends Scraper {

    protected $name = 'CryptoCompare';
    protected $base_uri = 'https://min-api.cryptocompare.com/data/price';

    public function uri()
    {
        return $this->base_uri . '?' . http_build_query([
            'fsym' => $this->from_symbol,
            'tsyms' => $this->to_symbol,
            'extraParams' => 'LedgerPrices', // optional name of app using their API
        ]);
    }

    public function scrape() {
        try {
            $json = json_decode($this->response->getBody(), true);
            if (empty($json)) {
                throw new ScraperException('No valid JSON in response');
            }
            if (!isset($json[$this->to_symbol])) {
                throw new ScraperException("No valid {$this->to_symbol} rate in JSON response");
            }
            $rate = (float)$json[$this->to_symbol];
            if ($rate === null || $rate <= 0) {
                throw new ScraperException("Missing rate for {$this->to_symbol} in JSON response");
            }
        } catch (RequestException $e) {
            throw new ScraperException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->formatRate($rate);
    }
}
