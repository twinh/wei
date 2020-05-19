isIdCardCn
==========

检查数据是否为有效的中国身份证

案例
----

### 检查15位数字"342622840209049"是否为有效的中国身份证

```php
if (wei()->isIdCardCn('342622840209049')) {
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

名称                    | 信息
------------------------|------
notStringMessage        | %name%必须是字符串
invalidMessage          | %name%必须是有效的中国身份证
negativeMessage         | %name%不能是有效的中国身份证

### 方法

#### isIdCardCn($input)
检查数据是否为有效的中国身份证

相关链接
--------

* [验证器概览](../book/validators.md)