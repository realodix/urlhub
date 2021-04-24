<?php

// PHP-CS-Fixer v3
// Based on https://docs.styleci.io/presets#laravel
$rules = [
    '@Symfony' => true,
    'array_indentation' => true,
    'array_syntax' => ['syntax' => 'short'], // short_array_syntax
    'compact_nullable_typehint' => true,
    'heredoc_to_nowdoc' => true,
    'increment_style' => ['style' => 'post'], // post_increment
    'list_syntax' => ['syntax' => 'short'], // short_list_syntax
    'multiline_whitespace_before_semicolons' => true, // no_multiline_whitespace_before_semicolons
    'no_alias_functions' => true, // @Symfony:risky, @PhpCsFixer:risky
    'no_extra_blank_lines' => [
        'tokens' => [
            'extra',
            'throw', // no_blank_lines_after_throw
            'use', // no_blank_lines_between_imports
            'use_trait' // no_blank_lines_between_traits
        ]
    ],
    'no_spaces_around_offset' => ['positions' => ['inside']], // no_spaces_inside_offset
    'no_unreachable_default_argument_value' => true, // @PhpCsFixer:risky
    'no_useless_return' => true,
    'no_whitespace_in_blank_line' => true,
    'not_operator_with_successor_space' => true,
    'psr_autoloading' => true, // @Symfony:risky, @PhpCsFixer:risky
    'self_accessor' => true, // @Symfony:risky, @PhpCsFixer:risky
    'simplified_null_return' => true,
    'new_with_braces' => false,
    'no_break_comment' => false,
    'no_empty_comment' => false,
    'no_superfluous_phpdoc_tags' => false,
    'php_unit_fqcn_annotation' => false,
    'phpdoc_annotation_without_dot' => false,
    'phpdoc_return_self_reference' => false,
    'phpdoc_separation' => false,
    'phpdoc_to_comment' => false,
    'phpdoc_trim_consecutive_blank_line_separation' => false,
    'phpdoc_types_order' => false,
    'semicolon_after_instruction' => false,
    'single_line_throw' => false,
    'single_trait_insert_per_statement' => false,
    'standardize_increment' => false,
    'yoda_style' => false,

    // Custom rules
    'binary_operator_spaces' => ['operators' => ['=>' => 'align']], // unalign_equals
    'no_empty_phpdoc' => false,
    'phpdoc_summary' => false,

    // Additional rules
    'compact_nullable_typehint' => true,
    'fully_qualified_strict_types' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'phpdoc_align' => [ // align_phpdoc
        'tags' => [
            // 'return',
            'param', 'throws','type','var'
        ]
    ],
    'phpdoc_to_comment' => true,
    'phpdoc_var_annotation_correct_order' => true,
];

$excludes = [
    'bootstrap/cache',
    'config',
    'node_modules',
    'public',
    'storage'
];

$finder = PhpCsFixer\Finder::create()
    ->exclude($excludes)
    ->in(__DIR__)
    ->notName('*.blade.php')
    ->notName('.phpstorm.meta.php')
    ->notName('_ide_*.php');

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setLineEnding("\r\n")
    ->setFinder($finder);
