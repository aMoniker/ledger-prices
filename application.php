<?php

namespace aMoniker\LedgerPrices;

use aMoniker\LedgerPrices\Command\UpdatePricesCommand;
use Symfony\Component\Console\Application;

$app = new Application();
$app->add(new UpdatePricesCommand());
$app->run();
