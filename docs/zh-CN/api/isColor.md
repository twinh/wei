isColor
=======

检查数据是否为有效的十六进制颜色

案例
----

### 检查"#FF0000"是否为有效的十六进制颜色

```php
if (wei()->isColor('#FF0000')) {
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
patternMessage          | %name%必须是有效的十六进制颜色,例如#FF0000
negativeMessage         | %name%不能是有效的十六进制颜色

### 方法

#### isColor($input)
检查数据是否为有效的十六进制颜色

相关链接
--------

* [验证器概览](../book/validators.md)