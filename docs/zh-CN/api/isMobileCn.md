isMobileCn
==========

检查数据是否为有效的中国手机号码

案例
----

### 检查"13800138000"是否为有效的手机号码
```php
if (widget()->isMobileCn('13800138000')) {
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

| 名称              | 类型    | 默认值                                     | 说明    |
|-------------------|---------|--------------------------------------------|---------|
| notStringMessage  | string  | %name%必须是字符串                         | -       |
| patternMessage    | string  | %name%必须是13位长度的数字,以13,15或18开头 | -       |
| negativeMessage   | string  | %name%必须不匹配模式"%pattern%"            | -       |

### 方法

#### isMobileCn($input)
