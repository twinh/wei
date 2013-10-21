isPlateNumberCn
===============

检查数据是否为有效的中国车牌号码

案例
----

### 检查"京A12345"是否为有效的车牌号码

```php 
if (wei()->isPlateNumberCn('京A12345')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

##### 运行结果

```php
'Yes'
```

### 检查"粤A12345"是否为广东的车牌号码

```php
$number = '粤A12345';
if (wei()->isStartsWith($number, '粤') && wei()->isPlateNumberCn($number)) {
    echo 'Yes';
} else {
    echo 'No';
}
```

##### 运行结果

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
patternMessage         | %name%必须是正确的车牌格式
negativeMessage        | %name%不能是正确的车牌格式
notStringMessage       | %name%必须是字符串

### 方法

#### isPlateNumberCn($input)
检查数据是否为有效的中国车牌号码

相关链接
--------

* [验证器概览](../book/validators.md)