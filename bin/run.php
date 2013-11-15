<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Command\DumpDatabaseCommand;

$app = new Application('Examples', '0.0.1');
$app->add(new DumpDatabaseCommand());
$app->run();
