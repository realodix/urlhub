<?php

use Realodix\PhpCsFixerConfig\Factory;
use Realodix\PhpCsFixerConfig\RuleSet;

$config = Factory::fromRuleSet(new RuleSet\Realodix());

$excludes = [
    'bootstrap/cache',
    'config',
    'node_modules',
    'public',
    'storage'
];

$config->getFinder()
    ->in(__DIR__)
    ->exclude($excludes);

return $config;
