<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\FunctionNotation\SingleLineThrowFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitConstructFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertInternalTypeFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMockShortWillReturnFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\StringNotation\ExplicitStringVariableFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayListItemNewlineFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\ArrayOpenerAndCloserNewlineFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->parallel();

    $services = $ecsConfig->services();
    $services->set(ClassAttributesSeparationFixer::class)
        ->call('configure',
            [
                ['elements' => ['method' => 'one']],
            ]
        );
    $services->set(ConcatSpaceFixer::class)
        ->call('configure',
            [
                ['spacing' => 'one'],
            ]
        );
    $services->set(CastSpacesFixer::class)
        ->call('configure',
            [
                ['space' => 'one'],
            ]
        );
    $services->set(DeclareStrictTypesFixer::class);
    $services->set(PhpUnitConstructFixer::class);
    $services->set(PhpUnitDedicateAssertFixer::class)
        ->call('configure',
            [
                ['target' => 'newest'],
            ]
        );
    $services->set(PhpUnitDedicateAssertInternalTypeFixer::class);
    $services->set(PhpUnitMockFixer::class);
    $services->set(PhpUnitMockShortWillReturnFixer::class);
    $services
        ->set(PhpUnitTestCaseStaticMethodCallsFixer::class)
        ->call('configure',
            [
                ['call_type' => 'self'],
            ]
        );

    $ecsConfig->paths([
        __DIR__ . '/examples',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->skip([
        ArrayOpenerAndCloserNewlineFixer::class => null,
        ArrayListItemNewlineFixer::class => null,
        NotOperatorWithSuccessorSpaceFixer::class,
        SingleLineThrowFixer::class => null,
        ExplicitStringVariableFixer::class => null,
        PhpdocSummaryFixer::class => null,
        NoUnusedImportsFixer::class => null,
    ]);

    $ecsConfig->sets([
        SetList::ARRAY,
        SetList::CONTROL_STRUCTURES,
        SetList::SPACES,
        SetList::CLEAN_CODE,
        SetList::STRICT,
        SetList::PSR_12,
    ]);
};
