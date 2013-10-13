# 简介

Widget是一个PHP微框架,提供了强大又简洁的接口,让PHP开发更快速,更简单.

Widget的使用比任何框架都要简单,只需3步,加载=>创建=>调用!

```php
// 1. 加载核心类文件
require 'path/to/widget/lib/Widget/Widget.php';

// 2. 创建对象管理器对象
$widget = wei(array(
    // 对象管理器选项
    'widget' => array(
        'debug' => true,
        // 其他选项...
    ),
    // 数据库选项
    'db' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'dbname'    => 'widget',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => 'xxxxxx',
    ),
    // 更多选项...
));

// 3. 调用"db"对象执行SQL查询
$result = $widget->db->fetch("SELECT 1 + 2");
```

## 功能特性

* **微内核,高扩展**: 除了两个核心类文件之外,其他类都是扩展,按需加载,自由替换
* **统一入口**: 通过`wei()`方法获取任意框架对象,随时随地使用
* **灵活配置**: 配置项具体到类属性,自由灵活
* **无依赖**: 核心功能不依赖第三方类库,体积更小
* **免费开源**: 遵循MIT协议,可自由更改,商业使用

## 系统要求

PHP 5.3+
