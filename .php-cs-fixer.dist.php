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
    'phpdoc_order'      => false,
    'phpdoc_separation' => false,
    // Realodix
    'class_definition' => false,
    'new_with_braces'  => ['named_class' => false, 'anonymous_class' => false],
    'no_empty_comment' => false,
    'phpdoc_align'     => false,
    'phpdoc_annotation_without_dot' => false,
];

return Config::create(new Realodix($addOrOverrideRules))
    ->setFinder($finder);
