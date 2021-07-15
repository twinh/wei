<?php

$file = __DIR__ . '/vendor/miaoxing/dev/.php_cs';
if (is_file($file)) {
    $fixer = require $file;
} else {
    $fixer = require __DIR__ . '/../miaoxing-dev/.php_cs';
}

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

$fixer->setFinder(
    PhpCsFixer\Finder::create()
        ->in($dirs)
);

$fixer->setRules(array_merge($fixer->getRules(), [
    // 允许测试类继承测试类
    'final_internal_class' => false,
]));

return $fixer;
