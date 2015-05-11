<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->level(Symfony\CS\FixerInterface::NONE_LEVEL)
    ->fixers(array(
        'psr0',
        //PSR-1
        'encoding',
        'short_tag',
        //PSR-2
        'braces',
        'elseif',
        'eof_ending',
        'function_call_space',
        'function_declaration',
        'indentation',
        'line_after_namespace',
        'linefeed',
        'lowercase_constants',
        'lowercase_keywords',
        'method_argument_space',
        'multiple_use',
        'parenthesis',
        'php_closing_tag',
        'single_line_after_imports',
        'trailing_spaces',
        'visibility',
        // Symfony
        'blankline_after_open_tag',
        'double_arrow_multiline_whitespaces',
        'duplicate_semicolon',
        'empty_return',
        'extra_empty_lines',
        'include',
        'multiline_array_trailing_comma',
        'namespace_no_leading_whitespace',
        'new_with_braces',
        'object_operator',
        'operators_spaces',
        'phpdoc_scalar',
        'phpdoc_trim',
        'phpdoc_type_to_var',
        'return',
        'self_accessor',
        'single_array_no_trailing_comma',
        'single_blank_line_before_namespace',
        'single_quote',
        'spaces_before_semicolon',
        'spaces_cast',
        'standardize_not_equal',
        'ternary_spaces',
        'unalign_double_arrow',
        'unalign_equals',
        'unused_use',
        'whitespacy_lines',
        // Contrib
        'concat_with_spaces',
        'long_array_syntax',
        'multiline_spaces_before_semicolon',
        'newline_after_open_tag',
        'ordered_use',
        'phpdoc_order',
        'short_echo_tag',
        'strict',
        'strict_param',
    ))
    ->finder($finder);