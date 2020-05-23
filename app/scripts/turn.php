<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use roulette\App;
use roulette\Service\Logger;

$app = new App();

try {
    $app->nextTurn();
} catch (\Throwable $t){
    Logger::write($t->getMessage());
}