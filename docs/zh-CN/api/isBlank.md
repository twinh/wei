isBlank
=======

检查数据是否为空(不允许空格)

案例
----

### 检查空白字符会返回成功
```php
$input = '    ';
if (wei()->isBlank($input)) {
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

| 名称                | 类型    | 默认值                                 | 说明              |
|---------------------|---------|----------------------------------------|-------------------|
| blankMessage        | string  | %name%必须为空                         | -                 |
| negativeMessage     | string  | %name%不能为空                         | -                 |

### 方法

#### isBlank($input)
检查数据是否为空(不允许空格)

**返回:** `bool` 检查结果

**参数**

名称   | 类型   | 说明
-------|--------|------
$input | string | 要检查的数据

相关链接
--------

* [验证器概览](../book/validators.md)
* [区分required,notBlank和present验证规则](validate.md#%E6%A1%88%E4%BE%8B%E5%8C%BA%E5%88%86requirednotblank%E5%92%8Cpresent%E9%AA%8C%E8%AF%81%E8%A7%84%E5%88%99)