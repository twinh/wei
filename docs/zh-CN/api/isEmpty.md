isEmpty()
=========

检查数据是否为空(允许空格)

案例
----

### 检查"abc"是否为空
```php
if (widget()->isEmpty('abc')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'No'
```

### 检查空格字符串是否为空
```php
if (widget()->isEmpty(' ')) {
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

| 名称                | 类型    | 默认值                                 | 说明              |
|---------------------|---------|----------------------------------------|-------------------|
| emptyMessage        | string  | %name%必须为空                         | -                 |
| negativeMessage     | string  | %name%不能为空                         | -                 |

### 方法

#### isEmpty($input)
检查数据是否为空(允许空格)
