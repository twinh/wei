isBetween
=========

检查数据是否在指定的两个值之间,不包含值本身($min < $input < $max)

案例
----

### 检查18是否在1到10之间

```php
if (widget()->isBetween(18, 1, 10)) {
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
| min                 | int     | 无                                     | 用于比较的较小值  |
| max                 | int     | 无                                     | 用于比较的较大值  |
| notStringMessage    | string  | %name%必须是字符串                     | -                 |
| betweenMessage      | string  | %name%必须在%min%到%max%之间           | -                 |
| negativeMessage     | string  | %name%不能在%min%到%max%之间           | -                 |

### 方法

#### isBetween($input, $min, $max)
检查数据是否在指定的两个值之间