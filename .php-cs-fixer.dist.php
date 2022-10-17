<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        // > PHPUnit
        'php_unit_method_casing' => ['case' => 'snake_case'],
    ])->setFinder($finder);
