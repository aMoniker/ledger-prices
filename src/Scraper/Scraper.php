<?php

namespace aMoniker\LedgerPrices\Scraper;

use aMoniker\LedgerPrices\Exception\ScraperException;
use GuzzleHttp\Client;

abstract class Scraper {

    /**
     * A name for display purposes.
     *
     * @var string
     */
    protected $name = 'Base';

    /**
     * The external symbol to be converted from.
     *
     * @var string
     */
    protected $from_symbol;

    /**
     * The external symbol to be converted to.
     *
     * @var string
     */
    protected $to_symbol;

    /**
     * The URI of the request.
     * By default, a GET request will be issued to this URI.
     * If you need a dynamic URI, override the Scraper::uri() function.
     *
     * @var string
     */
    protected $base_uri;

    /**
     * The HTTP method used in the Guzzle request
     *
     * @var string
     */
    protected $http_method = 'GET';

    /**
     * The Guzzle client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * The Guzzle response object.
     *
     * @var \Guzzle\Http\Message\Response
     */
    protected $response;

    /**
     * Sets any options and create the Guzzle client
     */
    public function __construct()
    {
        $options = [];
        if ($this->base_uri) {
            $options['base_uri'] = $this->base_uri;
        }

        $this->guzzle = new Client($options);
    }

    /**
     * Fetch the URI and return the result of Scraper::scrape()
     *
     * @param  string $symbol Optional symbol that the scraper should use.
     * @return float
     */
    public function process($from_symbol, $to_symbol)
    {
        $this->from_symbol = $from_symbol;
        $this->to_symbol = $to_symbol;

        try {
            $this->fetch();
            return $this->scrape();
        } catch (ScraperException $e) {
            // TODO log this
            return null;
        }
    }

    /**
     * Return the name of the scraper
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Return the URI used by Scraper::fetch()
     *
     * @return string
     */
    protected function uri()
    {
        return $this->base_uri;
    }

    /**
     * Fetch the Scraper::uri() and save the Guzzle response
     */
    protected function fetch() {
        $this->response = $this->guzzle->request($this->http_method, $this->uri());
    }

    /**
     * Each subclass must implement this function.
     *
     * It should use $this->data, obtained in Scraper::fetch()
     * and return a float value designating the current exchange rate.
     *
     * @return float The rate of exchange for the given commodity
     */
    protected abstract function scrape();

    /**
     * Format the returned rate as a plain object
     * with the from/to symbols and the rate.
     *
     * @param  float $rate
     * @return object
     */
    protected function formatRate($rate)
    {
        return (object) [
            'from' => $this->from_symbol,
            'to'   => $this->to_symbol,
            'rate' => $rate,
        ];
    }
}
