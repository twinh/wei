isDigit
=======

检查数据是否只由数字组成

案例
----

### 检查"abc123"是否为数字

```php
if (wei()->isDigit('abc123')) {
    echo 'Yes';
} else {
    echo 'No';
}
// 输出了'No'
```

调用方式
--------

### 选项

*无*

### 错误信息

名称                    | 信息
------------------------|------
patternMessage          | %name%只能由数字(0-9)组成
negativeMessage         | %name%不能只由数字(0-9)组成
notStringSring          | %name%必须是字符串

### 方法

#### isDigit($input)
检查数据是否只由数字组成

相关链接
--------

* [验证器概览](../book/validators.md)
