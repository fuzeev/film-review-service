<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\CastNotation\LowercaseCastFixer;
use PhpCsFixer\Fixer\CastNotation\ShortScalarCastFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalInternalClassFixer;
use PhpCsFixer\Fixer\ClassNotation\NoBlankLinesAfterClassOpeningFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\FunctionNotation\PhpdocToReturnTypeFixer;
use PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer;
use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveIssetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveUnsetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareEqualNormalizeFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\Semicolon\NoSinglelineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\NoTrailingWhitespaceFixer;
use PHP_CodeSniffer\Standards\PSR1\Sniffs\Classes\ClassDeclarationSniff;
use PHP_CodeSniffer\Standards\PSR1\Sniffs\Files\SideEffectsSniff;
use PHP_CodeSniffer\Standards\PSR1\Sniffs\Methods\CamelCapsMethodNameSniff;
use Symplify\CodingStandard\Fixer\Strict\BlankLineAfterStrictTypesFixer;
use Symplify\CodingStandard\Fixer\ArrayNotation\StandaloneLineInMultilineArrayFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    // Пути, по которым производится поиск файлов для проверки
    $ecsConfig->paths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->import(__DIR__ . '/vendor/symplify/easy-coding-standard/config/set/clean-code.php');
    $ecsConfig->import(__DIR__ . '/vendor/symplify/easy-coding-standard/config/set/common.php');
    $ecsConfig->import(__DIR__ . '/vendor/symplify/easy-coding-standard/config/set/doctrine-annotations.php');
    $ecsConfig->import(__DIR__ . '/vendor/symplify/easy-coding-standard/config/set/psr12.php');
    $ecsConfig->import(__DIR__ . '/vendor/symplify/easy-coding-standard/config/set/symplify.php');

    // Отдельные правила без дополнительной конфигурации
    $ecsConfig->rule(StandaloneLineInMultilineArrayFixer::class);
    $ecsConfig->rule(BlankLineAfterStrictTypesFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer::class);
    $ecsConfig->rule(FinalInternalClassFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Alias\MbStrFunctionsFixer::class);
    $ecsConfig->rule(ClassDeclarationSniff::class);
    $ecsConfig->rule(SideEffectsSniff::class);
    $ecsConfig->rule(CamelCapsMethodNameSniff::class);
    $ecsConfig->rule(LowercaseCastFixer::class);
    $ecsConfig->rule(ShortScalarCastFixer::class);
    $ecsConfig->rule(BlankLineAfterOpeningTagFixer::class);
    $ecsConfig->rule(NoLeadingImportSlashFixer::class);
    $ecsConfig->rule(NoBlankLinesAfterClassOpeningFixer::class);
    $ecsConfig->rule(TernaryOperatorSpacesFixer::class);
    $ecsConfig->rule(ReturnTypeDeclarationFixer::class);
    $ecsConfig->rule(NoTrailingWhitespaceFixer::class);
    $ecsConfig->rule(NoSinglelineWhitespaceBeforeSemicolonsFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\ArrayNotation\WhitespaceAfterCommaInArrayFixer::class);
    $ecsConfig->rule(CombineConsecutiveIssetsFixer::class);
    $ecsConfig->rule(CombineConsecutiveUnsetsFixer::class);
    $ecsConfig->rule(PhpdocToReturnTypeFixer::class);
    $ecsConfig->rule(FullyQualifiedStrictTypesFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer::class);
    $ecsConfig->rule(BinaryOperatorSpacesFixer::class);
    $ecsConfig->rule(UnaryOperatorSpacesFixer::class);
    $ecsConfig->rule(NoUnusedImportsFixer::class);
    $ecsConfig->rule(SingleQuoteFixer::class);
    $ecsConfig->rule(\PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer::class);

    // Правила с конфигурацией
    $ecsConfig->ruleWithConfiguration(OrderedImportsFixer::class, [
        'imports_order' => ['class', 'const', 'function'],
    ]);

    $ecsConfig->ruleWithConfiguration(DeclareEqualNormalizeFixer::class, [
        'space' => 'none',
    ]);

    $ecsConfig->ruleWithConfiguration(VisibilityRequiredFixer::class, [
        'elements' => ['const', 'method', 'property'],
    ]);

    $ecsConfig->ruleWithConfiguration(OrderedClassElementsFixer::class, [
        'order' => ['use_trait'],
    ]);

    $ecsConfig->ruleWithConfiguration(ConcatSpaceFixer::class, [
        'spacing' => 'one',
    ]);

    $ecsConfig->ruleWithConfiguration(BlankLineBeforeStatementFixer::class, [
        'statements' => ['return'],
    ]);

    // Опционально: установка директории кэша
    $ecsConfig->cacheDirectory(__DIR__ . '/var/cache/ecs');
};
