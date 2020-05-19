isPresent
=========

检查数据是否为空(允许空格)

案例
----

### 检查"abc"是否不为空

```php
if (wei()->isPresent('abc')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'Yes'
```

### 检查空格字符串是否不为空

```php
if (wei()->isPresent(' ')) {
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

*无*

### 错误信息

名称                   | 信息
-----------------------|------
emptyMessage           | %name%必须为空
negativeMessage        | %name%不能为空

### 方法

#### isPresent($input)
检查数据是否不为空(允许空格)

相关链接
--------

* [验证器概览](../book/validators.md)
* [区分required,notBlank和present验证规则](validate.md#%E6%A1%88%E4%BE%8B%E5%8C%BA%E5%88%86requirednotblank%E5%92%8Cpresent%E9%AA%8C%E8%AF%81%E8%A7%84%E5%88%99)