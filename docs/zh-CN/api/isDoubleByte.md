isDoubleByte
============

检查数据是否只由双字节字符组成

案例
----

### 检查"中文abc"是否只由双字节字符组成

```php
if (wei()->isDoubleByte('中文abc')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'No'
```

调用方式
--------

### 选项

*无*

### 错误信息

名称                    | 信息
------------------------|------
notStringMessage        | %name%必须是字符串
patternMessage          | %name%只能由双字节字符组成
negativeMessage         | %name%不能只由双字节字符组成

### 方法

#### isDoubleByte($input)
检查数据是否只由双字节字符组成

相关链接
--------

* [验证器概览](../book/validators.md)