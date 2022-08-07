<?php

use Realodix\CsConfig\Config;
use Realodix\CsConfig\Finder;
use Realodix\CsConfig\Rules\Realodix;

$localRules = [
    // Base
    'binary_operator_spaces' => false,
    'single_import_per_statement' => false,
    'group_import' => true,

    // Realodix
    'no_empty_comment'  => false
];

return Config::create(new Realodix($localRules))
    ->setFinder(Finder::laravel());
