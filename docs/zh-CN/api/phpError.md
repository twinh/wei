PHP Error
=========

[PHP Error](http://phperror.net/)提供了更友好的错误提示信息,完整的语法和代码片段高亮,同时支持Ajax错误提示

案例
----

### 在Widget初始化时加载PHP Error

```php
wei(array(
    'wei' => array(
        'preload' => array(
            'phpError' // 在预加载选项增加phpError对象
        )
    ),
    // 配置phpError对象的选项
    'phpError' => array(
        'catch_ajax_errors' => true,
        'catch_class_not_found' => true,
        // more options
    )
));
```

调用方式
--------

### 选项

参见PHP Error官网文档https://github.com/JosephLenton/PHP-Error/wiki/Options

### 方法

#### phpError()
获取\php_error\ErrorHandler对象
