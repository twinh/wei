isUuid
======

检查数据是否为有效的UUID

案例
----

### 检查"00010203-0405-0607-0809-0A0B0C0D0E0F"是否为有效的UUID
```php
if (wei()->isUuid('00010203-0405-0607-0809-0A0B0C0D0E0F')) {
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
| patternMessage      | string  | %name%必须是有效的UUID           | -                 |
| negativeMessage     | string  | %name%不能是有效的UUID           | -                 |

### 方法

#### isUuid($input)
检查数据是否为有效的UUID

相关链接
--------

* [验证器概览](../book/validators.md)