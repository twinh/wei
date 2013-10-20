isIdCardCn
==========

检查数据是否为有效的中国身份证

案例
----

### 检查15位数字"342622840209049"是否为有效的中国身份证
```php
if (wei()->isIdCardCn('342622840209049')) {
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
| invalidMessage      | string  | %name%必须是有效的中国身份证     | -                 |
| negativeMessage     | string  | %name%不能是有效的中国身份证     | -                 |

### 方法

#### isIdCardCn($input)
检查数据是否为有效的中国身份证

相关链接
--------

* [验证器概览](../book/validators.md)