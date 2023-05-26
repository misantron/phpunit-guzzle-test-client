<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->parallel();

    $ecsConfig->paths([
        __DIR__ . '/examples',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->skip([
        NotOperatorWithSuccessorSpaceFixer::class,
    ]);

    $ecsConfig->sets([
        SetList::SPACES,
        SetList::ARRAY,
        SetList::CLEAN_CODE,
        SetList::STRICT,
        SetList::PSR_12,
        SetList::PHPUNIT,
        SetList::CONTROL_STRUCTURES,
        SetList::NAMESPACES,
    ]);

    $ecsConfig->ruleWithConfiguration(OrderedImportsFixer::class, [
        'sort_algorithm' => 'alpha',
        'imports_order' => [
            'const',
            'class',
            'function',
        ],
    ]);
};
