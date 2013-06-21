Server
======

获取服务器运行和环境参数($_SERVER)

案例
----

### 获取名称为iERVER_NAME的服务器运行参数
```php
// 假设 $_SERVER['SERVER_NAME'] = 'www.example.com';

// 返回www.example.com
$id = widget()->server('SERVER_NAME');
```

调用方法
--------

### 选项

*无*

### 方法

#### server($name, $default = null)
获取服务器参数($_SERVER),返回值类型为字符串

#### server->getRaw($name, $default = null)
获取服务器参数的原始值

#### server->getInt($name, $min = null, $max = null)
获取服务器参数,返回值类型为整形

#### server->getArray($name, $default = null)
获取服务器参数,返回值类型为数组

#### server->getInArray($name, array $array)
获取服务器参数,如果参数的值不在数组中,将返回数组的第一个值
