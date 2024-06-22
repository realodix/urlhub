<?php

use Realodix\Relax\Config;
use Realodix\Relax\Finder;
use Realodix\Relax\RuleSet\Sets\Realodix;

$localRules = [
    'single_import_per_statement' => false,
];

return Config::create(new Realodix, $localRules)
    ->setFinder(Finder::laravel())
    ->setCacheFile(__DIR__.'/.tmp/.php-cs-fixer.cache');
