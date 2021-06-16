<?php

use PhpCsFixer\Finder;
use Realodix\CsConfig\Factory;
use Realodix\CsConfig\RuleSet;

$overrideRules = [
    // Realodix
    'phpdoc_separation' => true,
    'phpdoc_add_missing_param_annotation' => false,
    'PhpCsFixerCustomFixers/phpdoc_no_superfluous_param' => false,
    'binary_operator_spaces' => false,
    'no_superfluous_elseif' => false,
];

$finder = Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return Factory::fromRuleSet(new RuleSet\Realodix, $overrideRules)
        ->setFinder($finder);
