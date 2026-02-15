<?php

use Realodix\Relax\Config;
use Realodix\Relax\Finder;

$rules = [
    '@Realodix/Relax' => true,
    'single_import_per_statement' => false,
];

return Config::this()
    ->setRules($rules)
    ->setFinder(Finder::laravel()->in(__DIR__))
    ->setCacheFile(__DIR__.'/.tmp/.php-cs-fixer.cache');
