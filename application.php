<?php

namespace aMoniker\LedgerPrices;

use aMoniker\LedgerPrices\Command\UpdatePricesCommand;
use aMoniker\LedgerPrices\Command\GetPricesCommand;
use Symfony\Component\Console\Application;

$app = new Application();
$app->addCommands([
    new UpdatePricesCommand(),
    new GetPricesCommand(),
]);
$app->run();
