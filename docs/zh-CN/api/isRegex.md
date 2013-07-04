isRegex
=======

检查数据是否匹配指定的正则表达式

案例
----

### 检查"abc"是否匹配正则表达式"/d/i"
```php
if (widget()->isRegex('abc', '/d/i')) {
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
| notStringMessage    | string  | %name%必须是字符串                     | -                 |
| patternMessage      | string  | %name%必须匹配模式"%pattern%"          | -                 |
| negativeMessage     | string  | %name%必须不匹配模式"%pattern%"        | -                 |

### 方法

#### isRegex($input)
检查数据是否匹配指定的正则表达式
