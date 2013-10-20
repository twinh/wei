isRequired
==========

检查数据是否为空

检查以下值会返回false,其他值(包括0)均返回true

* null
* '' (空字符串)
* false
* array() (空数组)

案例
----

### 检查null是否为空

```php
if (wei()->isRequired(null)) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'No'
```

### 检查0是否为空

```php
if (wei()->isRequired(0)) {
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

名称              | 类型    | 默认值           | 说明
------------------|---------|------------------|------
requiredMessage   | string  | %name%不能为空   | -
negativeMessage   | string  | %name%不合法     | -

### 方法

#### isRequired($input, $required = true)
检查数据是否为空

相关链接
--------

* [验证器概览](../book/validators.md)