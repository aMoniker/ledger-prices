<?php

namespace aMoniker\LedgerPrices;

use aMoniker\LedgerPrices\Config;

class PriceDB {
    protected $config;

    public function __construct()
    {
        $this->config = new Config();
    }

    public function update($symbol, $rate)
    {
        // P 2004/06/21 02:18:01 FEQTX $22.49
        $date = date('Y/m/d H:i:s');
        $line = "P $date $symbol \$$rate\n";
        file_put_contents($this->config->get('pricedb_file'), $line, FILE_APPEND);
    }
}
