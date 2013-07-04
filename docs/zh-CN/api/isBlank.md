isBlank
=======

检查数据是否为空(不允许空格)

案例
----

### 检查空白字符会返回成功
```php
$input = '    ';
if (widget()->isBlank($input)) {
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
