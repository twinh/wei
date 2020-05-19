isDecimal
=========

检查数据是否为小数

案例
----

### 检查"1.0.0"是否为小数

```php
if (wei()->isDecimal('1.0.0')) {
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
invalidMessage          | %name%必须是小数
negativeMessage         | %name%不能是小数

### 方法

#### isDecimal($input)
检查数据是否为小数

相关链接
--------

* [验证器概览](../book/validators.md)