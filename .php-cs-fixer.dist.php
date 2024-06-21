<?php

use Realodix\Relax\Config;
use Realodix\Relax\Finder;
use Realodix\Relax\RuleSet\Sets\Realodix;

$localRules = [
    // Base
    'binary_operator_spaces' => false,
    'single_import_per_statement' => false,

    // Realodix
    'no_empty_comment'  => false,
];

return Config::create(new Realodix, $localRules)
    ->setFinder(Finder::laravel())
    ->setCacheFile(__DIR__.'/.tmp/.php-cs-fixer.cache');
