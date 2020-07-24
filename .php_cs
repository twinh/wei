<?php

$cwd = getcwd();

$phpDirs = [
    'lib',
    'tests',
];
$dirs = [];
foreach ($phpDirs as $dir) {
    if (is_dir($cwd . '/' . $dir)) {
        $dirs[] = $cwd . '/' . $dir;
    }
}

return PhpCsFixer\Config::create()
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in($dirs)
    )
    ->setRules([
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'single_line_comment_style' => [
            // 允许 /* @see xxx */
            'comment_types' => ['hash'],
        ],
        'multiline_whitespace_before_semicolons' => false,
        // 会导致返回指针错误，暂时不要
        'return_assignment' => false,
        // 拆散代码逻辑
        'no_superfluous_elseif' => false,
        // 同上
        'no_useless_else' => false,
        // 同上
        'blank_line_before_statement' => false,
        // 暂时需要，保证文档一致
        'no_superfluous_phpdoc_tags' => false,
        // 会导致中文加入半角句号
        'phpdoc_summary' => false,
        // 和 PhpStorm 默认自动生成不一致
        'phpdoc_align' => false,
        'phpdoc_separation' => false,
        'phpdoc_order' => false,
        'phpdoc_no_empty_return' => false,
        'phpdoc_var_annotation_correct_order' => false,
        'phpdoc_to_comment' => false,
        // https://youtrack.jetbrains.com/issue/WI-36662
        'array_indentation' => false,
        // 破坏语义
        'phpdoc_no_alias_tag' => false,
        // 代码高亮可以清楚展示出来
        'explicit_string_variable' => false,
        'php_unit_test_class_requires_covers' => false,
        'php_unit_test_case_static_method_calls' => false,
        // 视图变量中应使用<?=
        'no_short_echo_tag' => false,
        // 视图变量中允许没有;
        'semicolon_after_instruction' => false,
        // 参数过长需换行
        'single_line_throw' => false,
        // 允许测试类继承测试类
        'final_internal_class' => false,

        // Risky
        'php_unit_strict' => false,
        'strict_comparison' => false,
        'native_function_invocation' => false,
    ]);
