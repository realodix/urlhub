<?php

use Realodix\CsConfig\Config;
use Realodix\CsConfig\Finder;
use Realodix\CsConfig\Rules\Realodix;

$finder = Finder::laravel(__DIR__)
    ->notName('.phpstorm.meta.php')
    ->notName('_ide_*.php');

$addOrOverrideRules = [
    // Base
    'binary_operator_spaces' => false,

    // Realodix
    'no_empty_comment'  => false,
    'single_import_per_statement' => ['group_to_single_imports' => false],
    // 'group_import' => true
];

return Config::create(new Realodix($addOrOverrideRules))
    ->setFinder($finder);
