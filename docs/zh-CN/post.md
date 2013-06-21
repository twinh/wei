Post
====

获取HTTP请求($_POST)数据

案例
----

### 获取名称为id的请求数据
```php
// 假设 $_POST['id'] = 5;

// 返回5
$id = widget()->post('id');
```

调用方法
--------

### 选项

*无*

### 方法

#### post($name, $default = null)
获取HTTP请求参数($_POST),返回值类型为字符串

#### post->getRaw($name, $default = null)
获取HTTP请求参数的原始值

#### post->getInt($name, $min = null, $max = null)
获取HTTP请求参数,返回值类型为整形

#### post->getArray($name, $default = null)
获取HTTP请求参数,返回值类型为数组

#### post->getInArray($name, array $array)
获取HTTP请求参数,如果参数的值不在数组中,将返回数组的第一个值
