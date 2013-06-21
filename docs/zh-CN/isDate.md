isDate
======

检查数据是否为合法的日期

案例
----

### 检查"2013-01-01"是否为合法的日期
```php
if (widget()->isDate('2013-01-01')) {
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
| format            | string  | Y-m-d                                                  | 日期格式             |
| notStringMessage  | string  | %name%必须是字符串                                     | -                    |
| invalidMessage    | string  | %name%必须是合法的日期时间                             | 当日期无法解析时出现 |
| formatMessage     | string  | %name%不是合法的日期,格式应该是%format%,例如:%example% | -                    |
| tooEarlyMessage   | string  | %name%必须晚于%after%                                  | -                    |
| tooLateMessage    | string  | %name%必须早于%before%                                 | -                    |
| negativeMessage   | string  | %name%不能是合法的日期                                 | -                    |

完整的日期格式可以查看PHP官方文档中关于[date](http://php.net/manual/zh/function.date.php)函数的格式说明. 

### 方法

#### isDate($input, $format = 'Y-m-d')
检查数据是否为合法的日期
