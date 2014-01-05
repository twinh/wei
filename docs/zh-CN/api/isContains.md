isContains
==========

检查数据是否在指定的字符串或正则中

案例
----

### 检查"h"是否在"hello"之中

```php
if (wei()->isContains('hello', 'h')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'Yes'
```

### 检查"hello"是否匹配正则"/H/i"

```php
if (wei()->isContains('hello', '/H/i', true)) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'Yes'
```

调用方式
--------

### 选项

名称              | 类型    | 默认值  | 说明
------------------|---------|---------|------
search            | array   | array() | 用于搜索的字符串或正则
regex             | bool    | false   | 是否用正则匹配

### 错误信息

名称                       | 信息
---------------------------|------
notContainsMessage         | %name% must contains %search%
negativeMessage            | %name% must not contains %search%

### 方法

#### isContains($input, $search, $regex = false)
检查数据是否在指定的字符串或正则中

相关链接
--------

* [验证器概览](../book/validators.md)