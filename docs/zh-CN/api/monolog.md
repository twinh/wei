Monolog
=======

获取Monolog对象或记录一条日志

案例
----

### 记录一条DEBUG级别的日志
```php
wei()->monolog()->debug('The logger is called');
```

调用方式
--------

### 选项

名称         | 类型     | 默认值  | 说明
-------------|----------|---------|------
handlers     | array    | 见下面  | Monolog的日志处理器

handlers的默认值是

```php
$handlers = array(
    // 键名表示处理器类的名称
    // 如`stream`表示`Monolog\Handler\StreamHandler`,`chromePHP`表示`Monolog\Handler\ChromePHPHandler`
    'stream' => array(
        // 数组的值表示处理器类的初始化参数
        'stream' => 'log/wei.log', // 日志所在的文件
        'level' => MonologLogger::DEBUG, // 最低记录的等级
    )
);
```

您可以查看monolog的项目主页,了解更多处理器和配置

https://github.com/Seldaek/monolog

### 方法

#### monolog()
获取Monolog对象

#### monolog($level, $message)
记录一条日志
