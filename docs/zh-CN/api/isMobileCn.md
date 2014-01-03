isMobileCn
==========

检查数据是否为有效的中国手机号码

案例
----

### 检查"13800138000"是否为有效的手机号码

```php
if (wei()->isMobileCn('13800138000')) {
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
patternMessage          | %name%必须是11位长度的数字,以13,14,15或18开头

### 方法

#### isMobileCn($input)

检查数据是否为有效的中国手机号码

相关链接
--------

* [验证器概览](../book/validators.md)