isTime
======

检查数据是否为合法的时间

案例
----

### 检查"12:00:00"是否为合法的时间
```php
if (wei()->isTime('12:00:00')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'Yes'
```

### 检查"12:00"是否为符合格式"i:s"的时间
```php
if (wei()->isTime('12:00', 'i:s')) {
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
| format            | string  | H:i:s                                                  | 日期格式             |
| notStringMessage  | string  | %name%必须是字符串                                     | -                    |
| invalidMessage    | string  | %name%必须是合法的日期时间                             | 当日期无法解析时出现 |
| formatMessage     | string  | %name%不是合法的日期,格式应该是%format%,例如:%example% | -                    |
| tooEarlyMessage   | string  | %name%必须晚于%after%                                  | -                    |
| tooLateMessage    | string  | %name%必须早于%before%                                 | -                    |
| negativeMessage   | string  | %name%不能是合法的日期                                 | -                    |

完整的日期格式可以查看PHP官方文档中关于[date](http://php.net/manual/zh/function.date.php)函数的格式说明. 

### 方法

#### isTime($input, $format = 'H:i:s')
检查数据是否为合法的日期

相关链接
--------

* [验证器概览](../book/validators.md)