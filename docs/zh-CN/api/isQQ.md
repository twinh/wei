isQQ
====

检查数据是否为有效的QQ号码

案例
----

### 检查"123456"是否为有效的QQ号码

```php
if (wei()->isQQ('123456')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'Yes'
```

### 检查"0123456"是否为有效的QQ号码

```php
if (wei()->isQQ('0123456')) {
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

名称                   | 信息
-----------------------|------
notStringMessage       | %name%必须是字符串
patternMessage         | %name%必须是有效的QQ号码
negativeMessage        | %name%不能是QQ号码

### 方法

#### isQQ($input)
检查数据是否为有效的QQ号码

相关链接
--------

* [验证器概览](../book/validators.md)