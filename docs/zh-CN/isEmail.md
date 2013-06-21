isEmail
=======

检查数据是否为有效的邮箱地址

案例
----

### 检查"example@example.com"是否为邮箱地址
```php
if (widget()->isEmail('example@example.com')) {
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

| 名称              | 类型    | 默认值                           | 说明                                             |
|-------------------|---------|----------------------------------|--------------------------------------------------|
| notStringMessage  | string  | %name%必须是字符串               | -                                                |
| formatMessage     | string  | %name%必须是有效的邮箱地址       | -                                                |
| negativeMessage   | string  | %name%不能是数字                 | -                                                |

### 方法

#### isEmail($input)
检查数据是否为有效的邮箱地址
