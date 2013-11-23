isEmail
=======

检查数据是否为有效的邮箱地址

案例
----

### 检查"example@example.com"是否为邮箱地址

```php
if (wei()->isEmail('example@example.com')) {
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
formatMessage          | %name%必须是有效的邮箱地址
negativeMessage        | %name%不能是数字

### 方法

#### isEmail($input)
检查数据是否为有效的邮箱地址

相关链接
--------

* [验证器概览](../book/validators.md)