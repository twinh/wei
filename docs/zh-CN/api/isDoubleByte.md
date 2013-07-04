isDoubleByte
============

检查数据是否只由双字节字符组成

案例
----

### 检查"中文abc"是否只由双字节字符组成
```php
if (widget()->isDoubleByte('中文abc')) {
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

| 名称                | 类型    | 默认值                                 | 说明              |
|---------------------|---------|----------------------------------------|-------------------|
| notStringMessage    | string  | %name%必须是字符串                     | -                 |
| patternMessage      | string  | %name%只能由双字节字符组成             | -                 |
| negativeMessage     | string  | %name%不能只由双字节字符组成           | -                 |

### 方法

#### isDoubleByte($input)
检查数据是否只由双字节字符组成
