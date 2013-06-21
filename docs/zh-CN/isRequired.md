isRequired
==========

检查数据是否为空

案例
----

### 检查null是否为空
```php
if (widget()->isRequired(null)) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'No'
```

### 检查null是否为空,第二个参数设为false
```php
if (widget()->isRequired(null, false)) {
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

| 名称              | 类型    | 默认值                                                 | 说明                 |
|-------------------|---------|--------------------------------------------------------|----------------------|
| requiredMessage   | string  | %name%不能为空                                         | -                    |
| negativeMessage   | string  | %name%不合法                                           | -                    |

### 方法

#### isRequired($input, $required = true)
检查数据是否为空
