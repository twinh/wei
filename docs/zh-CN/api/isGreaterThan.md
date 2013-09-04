isGreaterThan
=============

检查数据是否大于(>)指定的值

案例
----

### 检查10是否大于20

```php
if (widget()->isGreaterThan(10, 20)) {
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

名称              | 类型    | 默认值                             | 说明
------------------|---------|------------------------------------|------
value             | mixed   | 无                                 | 待比较的数值
invalidMessage    | string  | %name%必须大于等于%value%          | -
negativeMessage   | string  | %name%不合法                       | -

### 方法

#### isGreaterThan($input, $value)
检查数据是否大于(>)指定的值
