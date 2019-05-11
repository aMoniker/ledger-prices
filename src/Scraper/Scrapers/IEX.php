<?php

namespace aMoniker\LedgerPrices\Scraper\Scrapers;

use aMoniker\LedgerPrices\Scraper\Scraper;
use aMoniker\LedgerPrices\Exception\ScraperException;
use GuzzleHttp\Exception\RequestException;

/**
 * Uses the IEX API to obtain stock quotes
 */
class IEX extends Scraper {

    protected $name = 'IEX';
		protected $base_uri = "https://api.iextrading.com/1.0/stock/";
		// https://api.iextrading.com/1.0/stock/F/delayed-quote

    public function uri()
    {
        return "{$this->base_uri}{$this->from_symbol}/delayed-quote";
    }

    public function scrape() {
        try {
						$body = $this->response->getBody();
            $json = json_decode($body, true);
            if (empty($json)) {
                throw new ScraperException('No valid JSON in response');
            }
						if (!isset($json['delayedPrice'])) {
                throw new ScraperException("Missing rate for {$this->from_symbol} in JSON response");
						}

						$rate = (float) $json['delayedPrice'];
            if ($rate === null || $rate <= 0) {
                throw new ScraperException("Invalid {$this->from_symbol} rate in JSON response");
            }
        } catch (RequestException $e) {
            throw new ScraperException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->formatRate($rate);
    }
}
