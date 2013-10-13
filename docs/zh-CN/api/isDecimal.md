isDecimal
=========

检查数据是否为小数

案例
----

### 检查"1.0.0"是否为小数
```php
if (wei()->isDecimal('1.0.0')) {
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
| invalidMessage      | string  | %name%必须是小数                       | -                 |
| negativeMessage     | string  | %name%不能是小数                       | -                 |

### 方法

#### isDecimal($input)
检查数据是否为小数
