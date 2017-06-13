<?php

namespace aMoniker\LedgerPrices\Scraper;

use aMoniker\LedgerPrices\Scraper\Scraper;
use aMoniker\LedgerPrices\Exception\ScraperException;
use GuzzleHttp\Exception\RequestException;

class ScraperXRP extends Scraper {

    protected $name = 'Ripple';
    protected $base_uri = 'https://data.ripple.com/v2/exchange_rates/XRP/USD+';
    protected $xrp_issuer_address = 'rvYAfWj5gh67oV6fW32ZzP3Aw4Eubs59B';

    public function uri()
    {
        return $this->base_uri . $this->xrp_issuer_address;
    }

    public function scrape() {
        try {
            $json = json_decode($this->response->getBody(), true);
            if (empty($json)) {
                throw new ScraperException('No valid JSON in response');
            }
            if (!isset($json['rate'])) {
                throw new ScraperException('No valid XRP rate in JSON response');
            }
            $rate = (float)$json['rate'];
            if ($rate <= 0) {
                throw new ScraperException('Zero rate value for XRP in JSON response');
            }
        } catch (RequestException $e) {
            throw new ScraperException($e->getMessage(), $e->getCode(), $e);
        }

        return $rate;
    }
}
