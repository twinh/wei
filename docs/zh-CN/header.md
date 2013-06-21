Header
======

设置和获取HTTP响应头

案例
----

### 设置和获取自定义HTTP响应头
```php
widget()->header('X-Powered-By', 'PHP');

// 输出'PHP'
echo widget()->header('X-Powered-By');
```

调用方式
--------

### 选项

*无*

### 方法

#### header($name)
获取HTTP响应头信息

#### header($name, $values, $replace = true)
设置HTTP响应头信息

| 名称          | 类型      | 默认值    | 说明                 |
|---------------|-----------|-----------|----------------------|
| $name         | string    | 无        | HTTP响应头的名称     |
| $values       | string    | 无        | HTTP响应头的值       |
| $replace      | bool      | true      | 是否替换已有的响应头 |

#### remove($name)
删除HTTP响应头信息
