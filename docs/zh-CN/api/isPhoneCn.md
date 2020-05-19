isPhoneCn
=========

检查数据是否为有效的中国电话号码

案例
----

### 检查"020-1234567"是否为电话号码

```php
if (wei()->isPhoneCn('020-1234567')) {
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

### 错误信息

名称                | 信息
--------------------|------
notStringMessage    | %name%必须是字符串
patternMessage      | %name%必须是有效的电话号码
negativeMessage     | %name%不能是电话号码

### 方法

#### isPhoneCn($input)
检查数据是否为有效的电话号码

相关链接
--------

* [验证器概览](../book/validators.md)
* [检查数据是否为通用电话号码:isPhone](isPhone.md)