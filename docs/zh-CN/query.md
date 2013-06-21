Query
=====

获取HTTP请求($_GET)数据

案例
----

### 获取名称为id的请求数据
```php
// 假设 $_GET['id'] = 5;

// 返回5
$id = widget()->query('id');
```

调用方法
--------

### 选项

*无*

### 方法

#### query($name, $default = null)
获取HTTP请求参数($_GET),返回值类型为字符串

#### query->getRaw($name, $default = null)
获取HTTP请求参数的原始值

#### query->getInt($name, $min = null, $max = null)
获取HTTP请求参数,返回值类型为整形

#### query->getArray($name, $default = null)
获取HTTP请求参数,返回值类型为数组

#### query->getInArray($name, array $array)
获取HTTP请求参数,如果参数的值不在数组中,将返回数组的第一个值
