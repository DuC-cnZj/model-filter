<?php
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => array('syntax' => 'short'),
        'combine_consecutive_unsets' => true,
        'elseif' => true,
        'explicit_indirect_variable' => true,
        'method_separation' => true,
        'no_multiline_whitespace_before_semicolons' => true,
        'single_quote' => true,
        'ordered_imports' => ['sortAlgorithm' => 'length'],
        'not_operator_with_successor_space' => true,
        'align_multiline_comment' => true,
        'no_trailing_whitespace' => true,
        'binary_operator_spaces' => array(
            'align_double_arrow' => true,
            'align_equals' => false,
        ),
        'blank_line_after_opening_tag' => true,
        'blank_line_before_return' => true,
        'combine_consecutive_issets' => true,
        'braces' => array(
            'allow_single_line_closure' => true,
        ),
        'concat_space' => array('spacing' => 'one'),
        'declare_equal_normalize' => true,
        'function_typehint_space' => true,
        'list_syntax' => [
            'syntax' => 'short',
        ],
        'method_chaining_indentation' => true,
        'hash_to_slash_comment' => true,
        'include' => true,
        'lowercase_cast' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_empty_statement' => true,
        'no_extra_consecutive_blank_lines' => array(
            'curly_brace_block',
            'extra',
            'parenthesis_brace_block',
            'square_brace_block',
            'throw',
            'use',
        ),
        'no_leading_import_slash' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_spaces_around_offset' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'single_blank_line_before_namespace' => true,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline_array' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true,
        'blank_line_after_namespace' => true,
        'no_unused_imports' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'object_operator_without_whitespace' => true,
        'no_short_echo_tag' => true,
        'class_attributes_separation' => true,
        'no_leading_namespace_whitespace' => true,
        'array_indentation' => true,
        'no_empty_comment' => true,
        'lowercase_constants' => true,
        'lowercase_static_reference' => true,
        'native_function_casing' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('tests/')
            ->in(__DIR__)
    )
;