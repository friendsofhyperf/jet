<?php

$header = <<<'EOF'
This file is part of friendsofhyperf/jet.

@link     https://github.com/friendsofhyperf/jet
@document https://github.com/friendsofhyperf/jet/blob/main/README.md
@contact  huangdijia@gmail.com
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'combine_consecutive_unsets' => true,
        'header_comment' => array('header' => $header),
        'array_syntax' => array('syntax' => 'long'),
        'no_extra_consecutive_blank_lines' => array('break', 'continue', 'extra', 'return', 'throw', 'use', 'parenthesis_brace_block', 'square_brace_block', 'curly_brace_block'),
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'php_unit_strict' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'psr4' => true,
        'strict_comparison' => true,
        'strict_param' => true,
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('tests')
            ->in(__DIR__)
    )
;