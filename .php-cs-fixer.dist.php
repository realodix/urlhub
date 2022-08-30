<?php

use Realodix\Relax\Config;
use Realodix\Relax\Finder;

$localRules = [
    // Base
    'binary_operator_spaces' => false,
    'single_import_per_statement' => false,
    'group_import' => true,

    // Realodix
    'no_empty_comment'  => false,
    'no_superfluous_phpdoc_tags' => false,
];

return Config::create('@Realodix', $localRules)
    ->setFinder(Finder::laravel());
