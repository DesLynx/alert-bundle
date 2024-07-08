<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__.'/src',
    ])
    ->append([__FILE__])
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
    ->setRules(
        [
            '@Symfony'               => true,
            '@Symfony:risky'         => true,
            'fopen_flags'            => false,
            'protected_to_private'   => false,
            'combine_nested_dirname' => true,
            'error_suppression'      => false,
            'binary_operator_spaces' => [
                'default' => 'align_single_space_minimal',
            ],
            'declare_equal_normalize' => [
                'space' => 'single',
            ],
            'native_function_invocation' => [
                'scope' => 'namespaced',
            ],
            'no_superfluous_phpdoc_tags' => false,
        ]
    )
    ->setFinder($finder)
;
