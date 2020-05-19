isAlnum
=======

检查数据是否只由字母(a-z)和数字(0-9)组成

案例
----

### 检查数据是否只由字母和数字组成

```php
$input = 'abc123';
if (wei()->isAlnum($input)) {
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

*无*

### 错误信息

名称                   | 信息
-----------------------|------
notStringMessage       | %name%必须是字符串
patternMessage         | %name%只能由字母(a-z)和数字(0-9)组成
negativeMessage        | %name%不能只由字母(a-z)和数字(0-9)组成

### 方法

#### isAlnum($input)
检查数据是否只由字母(a-z)和数字(0-9)组成

相关链接
--------

* [验证器概览](../book/validators.md)