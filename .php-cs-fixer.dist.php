<?php

use Realodix\Relax\Config;
use Realodix\Relax\Finder;

$localRules = [
    'single_import_per_statement' => false,
];

return Config::create('Realodix')
    ->setRules($localRules)
    ->setFinder(Finder::laravel())
    ->setCacheFile(__DIR__.'/.tmp/.php-cs-fixer.cache');
