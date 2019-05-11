<?php

namespace aMoniker\LedgerPrices;

use aMoniker\LedgerPrices\Command\UpdatePricesCommand;
use aMoniker\LedgerPrices\Command\DisplayPricesCommand;
use Symfony\Component\Console\Application;

date_default_timezone_set('America/New_York');

$app = new Application();
$app->addCommands([
    new UpdatePricesCommand(),
    new DisplayPricesCommand(),
]);
$app->run();
