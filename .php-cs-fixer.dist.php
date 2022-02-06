<?php

use PhpCsFixer\Finder;
use Realodix\CsConfig\Factory;
use Realodix\CsConfig\RuleSet;

$overrideRules = [
    // Realodix
    'phpdoc_align' => false,
    'phpdoc_order' => false,
    'phpdoc_separation' => false,
    // RelodixPlus
    'PhpCsFixerCustomFixers/no_useless_comment' => false,
    'binary_operator_spaces' => false,
    'general_phpdoc_annotation_remove' => false,
    'no_superfluous_elseif' => false,

    // Fixed
    'align_multiline_comment' => [
        'comment_type' => 'phpdocs_like',
    ],
];

$excludes = [
    'bootstrap/cache',
    'config',
    'node_modules',
    'public',
    'storage'
];

$finder = Finder::create()
    ->exclude($excludes)
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->notName('.phpstorm.meta.php')
    ->notName('_ide_*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return Factory::fromRuleSet(new RuleSet\RealodixPlus, $overrideRules)
        ->setFinder($finder);
