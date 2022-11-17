<?php

use Realodix\Relax\Config;
use Realodix\Relax\Finder;

$localRules = [
    // Base
    'binary_operator_spaces' => false,
    'single_import_per_statement' => false,

    // Realodix
    'no_empty_comment'  => false,
];

return Config::create('@Realodix', $localRules)
    ->setFinder(Finder::laravel());
