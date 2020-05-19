isIdCardMo
==========

检查数据是否为有效的澳门身份证

案例
----

### 检查"11111111"是否为有效的澳门身份证
```php
if (wei()->isIdCardMo('11111111')) {
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

| 名称                | 类型    | 默认值                           | 说明              |
|---------------------|---------|----------------------------------|-------------------|
| notStringMessage    | string  | %name%必须是字符串               | -                 |
| patternMessage      | string  | %name%必须是有效的澳门身份证     | -                 |
| negativeMessage     | string  | %name%不能是有效的澳门身份证     | -                 |

### 方法

#### isIdCardMo($input)
检查数据是否为有效的澳门身份证

相关链接
--------

* [验证器概览](../book/validators.md)