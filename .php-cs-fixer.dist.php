<?php

declare(strict_types=1);

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => false,
        'array_syntax' => ['syntax' => 'short'],
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'explicit_indirect_variable' => true,
        'explicit_string_variable' => true,
        'linebreak_after_opening_tag' => true,
        'method_chaining_indentation' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'continue',
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'use',
                'use_trait',
            ]
        ],
        'no_null_property_initialization' => true,
        'echo_tag_syntax' => true,
        'no_superfluous_elseif' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'ordered_imports' => true,
        'php_unit_construct' => true,
        'php_unit_test_class_requires_covers' => true,
        'phpdoc_order' => true,
        'return_assignment' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'yoda_style' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(['src', 'tests'])
            ->append(['.php_cs.dist'])
    )
;
