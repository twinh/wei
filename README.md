# Widget 0.9.3-RC1 [![Build Status](https://travis-ci.org/twinh/widget.png?branch=master)](https://travis-ci.org/twinh/widget)

Widget is a PHP micro-framework design for human to create wonderful web application.

provided a new way to write PHP for faster and easier web development.

# Installation

## Composer

Define the following requirement in your `composer.json` file and run `php composer.phar install` to install
```json
{
    "require": {
        "widget/widget": "0.9.3-RC1"
    }
}
```

## Download zip file

# Getting started

Start using Widget in 3 steps
Just 3 steps to start using Widget, it's more simply than what you have ever seen before

```php
// 1. load the widget manager class
require 'path/to/widget/lib/Widget/Widget.php';

// 2. Create the default widget manager instance
$widget = widget();

// 3. Invoke the query widget to receive the URL query parameter
$id = $widget->query('id');
```

Configuration
-------------

Why use Widget, not [other framework X]?

* 1 entry
* 2 core files, realy
* 3 steps to 
* Design for human, not for computer

# Testing

To run the tests:

    $ phpunit

# Change Log

see [CHANGELOG.md](CHANGELOG.md)

License
-------
Widget is an open-source project released MIT license. See the LICENSE file for details.


# Widget 0.9.3-RC1
Widget是一个PHP微框架,

安装 - Composer

安装 - 直接下载


```php
// 加载核心类文件
require 'path/to/widget/lib/Widget/Widget.php';

// 创建微件管理器对象
$widget = widget();

// 调用query微件,获取URL请求中id参数
$id = $widget->query('id');
```