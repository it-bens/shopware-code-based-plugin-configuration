<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedInterfacesFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer;
use Symplify\CodingStandard\Fixer\Spacing\StandaloneLinePromotedPropertyFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);

    $ecsConfig->import(SetList::COMMON);
    $ecsConfig->import(SetList::CLEAN_CODE);
    $ecsConfig->import(SetList::SYMPLIFY);
    $ecsConfig->import(SetList::PSR_12);
    $ecsConfig->import(SetList::DOCTRINE_ANNOTATIONS);

    $ecsConfig->ruleWithConfiguration(LineLengthFixer::class, [
        LineLengthFixer::LINE_LENGTH => 140,
    ]);
    $ecsConfig->rule(StandaloneLinePromotedPropertyFixer::class);

    $ecsConfig->rule(OrderedImportsFixer::class);
    $ecsConfig->rule(OrderedInterfacesFixer::class);
    $ecsConfig->ruleWithConfiguration(OrderedClassElementsFixer::class, [
        'sort_algorithm' => OrderedClassElementsFixer::SORT_ALPHA,
    ]);
};
