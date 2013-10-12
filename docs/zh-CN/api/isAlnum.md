isAlnum
=======

检查数据是否只由字母(a-z)和数字(0-9)组成

案例
----

### 检查数据是否只由字母和数字组成
```php
$input = 'abc123';
if (wei()->isAlnum($input)) {
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
| notStringMessage    | string  | %name%必须是字符串                     | -                 |
| patternMessage      | string  | %name%只能由字母(a-z)和数字(0-9)组成   | -                 |
| negativeMessage     | string  | %name%不能只由字母(a-z)和数字(0-9)组成 | -                 |

### 方法

#### isAlnum($input)
检查数据是否只由字母(a-z)和数字(0-9)组成
