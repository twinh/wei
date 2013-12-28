isDivisibleBy
=============

检查数据是否能被指定的除数整除

案例
----

### 检查10能否被3整除
```php
if (wei()->isDivisibleBy(10, 3)) {
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
notDivisibleMessage     | %name%必须被%divisor%整除
negativeMessage         | %name%不可以被%divisor%整除

### 方法

#### isDivisibleBy($input)
检查数据是否能被指定的除数整除

相关链接
--------

* [验证器概览](../book/validators.md)