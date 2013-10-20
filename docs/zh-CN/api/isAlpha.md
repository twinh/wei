isAlpha
=======

检查数据是否只由字母(a-z)组成

案例
----

### 检查数据是否只由字母组成
```php
$input = 'abc123';
if (wei()->isAlpha($input)) {
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

| 名称                | 类型    | 默认值                           | 说明              |
|---------------------|---------|----------------------------------|-------------------|
| notStringMessage    | string  | %name%必须是字符串               | -                 |
| patternMessage      | string  | %name%只能由字母(a-z)组成        | -                 |
| negativeMessage     | string  | %name%必须不匹配模式"%pattern%"  | -                 |

### 方法

#### isAlpha($input)
检查数据是否只由字母(a-z)组成

相关链接
--------

* [验证器概览](../book/validators.md)