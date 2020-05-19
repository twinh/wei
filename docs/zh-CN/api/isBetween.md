isBetween
=========

检查数据是否在指定的两个值之间,不包含值本身($min < $input < $max)

案例
----

### 检查18是否在1到10之间

```php
if (wei()->isBetween(18, 1, 10)) {
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

名称   | 类型    | 默认值  | 说明
-------|---------|---------|------
min    | int     | 无      | 用于比较的较小值
max    | int     | 无      | 用于比较的较大值

### 错误信息

名称                   | 信息
-----------------------|------
notStringMessage       | %name%必须是字符串
betweenMessage         | %name%必须在%min%到%max%之间
negativeMessage        | %name%不能在%min%到%max%之间

### 方法

#### isBetween($input, $min, $max)
检查数据是否在指定的两个值之间

相关链接
--------

* [验证器概览](../book/validators.md)