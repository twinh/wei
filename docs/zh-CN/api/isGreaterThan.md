isGreaterThan
=============

检查数据是否大于(>)指定的值

案例
----

### 检查10是否大于20

```php
if (wei()->isGreaterThan(10, 20)) {
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

名称     | 类型    | 默认值    | 说明
---------|---------|-----------|------
value    | mixed   | 无        | 待比较的数值

### 错误信息

名称                    | 信息
------------------------|------
invalidMessage          | %name%必须大于%value%
negativeMessage         | %name%不合法

### 方法

#### isGreaterThan($input, $value)
检查数据是否大于(>)指定的值

相关链接
--------

* [验证器概览](../book/validators.md)