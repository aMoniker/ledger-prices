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
     * The (optional) symbol to be scraped.
     *
     * @var string
     */
    protected $symbol;

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
    public function process($symbol = '')
    {
        try {
            $this->fetch();
            return $this->scrape();
        } catch (ScraperException $e) {
            // TODO log this
            return null;
        }
    }

    /**
     * Return the URI used by Scraper::fetch()
     *
     * @return string
     */
    public function uri()
    {
        return $this->base_uri;
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
     * Fetch the Scraper::uri() and save the Guzzle response
     */
    public function fetch() {
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
    public abstract function scrape();
}
