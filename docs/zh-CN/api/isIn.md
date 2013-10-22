isIn
====

检查数据是否在指定的数组中

案例
----

### 检查"1"是否在array(1, 2, 3)之中

```php
if (wei()->isIn(1, array(1, 2, 3))) {
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

名称              | 类型    | 默认值  | 说明
------------------|---------|---------|------
array             | mixed   | 无      | 用于搜索的数组
strict            | int     | 无      | 是否使用全等(===)进行比较,默认使用等于(==)比较

### 错误信息

名称                       | 信息
---------------------------|------
notInMessage               | %name%必须在指定的数据中:%array%
negativeMessage            | %name%不能在指定的数据中:%array%

### 方法

#### isIn($input, $array, $strict = false)
检查数据是否在指定的数组中

相关链接
--------

* [验证器概览](../book/validators.md)