isPositiveInteger
=================

检查数据是否为正整数(大于0的整数)

案例
----

### 检查"1"是否为正整数

```php
if (wei()->isNaturalNumber('1')) {
    echo 'Yes';
} else {
    echo 'No';
}
// 输出了'Yes'
```

### 检查"0"是否为自然数

```php
if (wei()->isNaturalNumber('0')) {
    echo 'Yes';
} else {
    echo 'No';
}
// 输出了'No'
```

### 使用场景

* 加入购物车的商品数量
* 用户年龄
* 积分、金币数量等

调用方式
--------

### 选项

*无*

### 错误信息

名称                       | 信息
---------------------------|------
invalidMessage             | %name%必须是大于0的整数

### 方法

#### isPositiveInteger($input)
检查数据是否为正整数(大于0的整数)

相关链接
--------

* [验证器概览](../book/validators.md)
* [检查数据是否为自然数(大于等于0的整数):isNaturalNumber](isNaturalNumber.md)
