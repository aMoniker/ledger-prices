<?php

namespace aMoniker\LedgerPrices;

use aMoniker\LedgerPrices\Config;

class PriceDB {
    protected $config;

    public function __construct()
    {
        $this->config = new Config();
    }

    /**
     * Update multiple rates at once.
     *
     * @param  array $rates in the format of 'external_symbol' => 'rate'
     */
    public function updateMultiple(array $rates = [])
    {
        $updated = 0;
        foreach ($rates as $rate) {
            $updated += ($this->update($rate->from, $rate->to, $rate->rate) ? 1 : 0);
        }
        return $updated;
    }

    public function update($from_symbol, $to_symbol, $rate)
    {
        $ledger_symbol = $this->ledgerSymbol($to_symbol);
        if (!$ledger_symbol) {
            // TODO - log this
            return false;
        }

        $date = date('Y/m/d H:i:s');
        $line = "P $date $from_symbol $ledger_symbol$rate\n";

        $written = file_put_contents($this->config->get('pricedb_file'), $line, FILE_APPEND);
        return ($written !== false);
    }

    protected function ledgerSymbol($external_symbol)
    {
        foreach ($this->config->get('conversion_symbols') as $internal => $external) {
            if (in_array($external_symbol, $external)) {
                return $internal;
            }
        }
        return null;
    }
}
