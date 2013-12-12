Autoload 自动加载
=================

微框架支持[PSR-0][1]和[PSR-4][2]两种类自动加载方式.

案例
----

### 启动自动加载

```php
wei(array(
    'wei' => array(
        'autoload' => true
    )
));
```

### 设置PSR-0类自动加载

```php
wei(array(
    'wei' => array(
        'autoloadMap' => array(
            '命名空间' => '类所在路径',
            'MyLib' => 'path/to/lib',
            'MyProject' => 'path/to/project',
            'MyClass\Module' => 'path/to/class',
            // 将未在上面指定命名空间的类,都引导到library目录下
            '' => 'path/to/library'
        )
    )
));
```

### 设置PSR-4类自动加载

```php
wei(array(
    'wei' => array(
        'autoloadMap' => array(
            '\命名空间' => '类所在路径',
            '\MyLib' => 'path/to/lib',
            '\MyProject' => 'path/to/project',
            '\MyClass\Module' => 'path/to/class'
        )
    )
));
```

[1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md