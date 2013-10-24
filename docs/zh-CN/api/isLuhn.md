isLuhn
======

检查数据是否符合Luhn算法

Luhn算法常用于信用卡号,防伪码校验

案例
----

### 检查信用卡号是否符合Luhn算法

```php
if (wei()->isLuhn('4111111111111111')) {
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

无

### 错误信息

名称                   | 信息
-----------------------|------
notStringMessage       | %name%必须是字符串
invalidMessage         | %name%不是有效的数字

### 方法

#### isLuhn($input)
检查数据是否符合Luhn算法

**返回:** `bool` 检查结果

**参数**

名称   | 类型   | 说明
-------|--------|------
$input | string | 要检查的数据

相关链接
--------

* [验证器概览](../book/validators.md)
* [信用卡号验证器:isCreditCard](isCreditCard.md)