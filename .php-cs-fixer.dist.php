<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('var');

$ruleSet = [
    '@Symfony' => true,
    // > PHPUnit
    'php_unit_method_casing' => ['case' => 'snake_case'],
    // > Strict
    'declare_strict_types' => true,
    // > Operator
    'not_operator_with_successor_space' => true,
    // > Cast Notation
    'cast_spaces' => ['space' => 'none'],
    // > Import
    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => false,
        'import_functions' => false,
    ],
];

$config = new PhpCsFixer\Config();

$config->setFinder($finder)->setRules($ruleSet)->setRiskyAllowed(true);

return $config;
