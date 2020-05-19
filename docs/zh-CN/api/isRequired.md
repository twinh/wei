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

*无*

### 错误信息

名称                    | 信息
------------------------|------
requiredMessage         | %name%不能为空
negativeMessage         | %name%不合法

### 方法

#### isRequired($input, $required = true)
检查数据是否为空

相关链接
--------

* [验证器概览](../book/validators.md)
* [区分required,notBlank和present验证规则](validate.md#%E6%A1%88%E4%BE%8B%E5%8C%BA%E5%88%86requirednotblank%E5%92%8Cpresent%E9%AA%8C%E8%AF%81%E8%A7%84%E5%88%99)