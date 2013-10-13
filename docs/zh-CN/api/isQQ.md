isQQ
====

检查数据是否为有效的QQ号码

案例
----

### 检查"123456"是否为有效的QQ号码
```php
if (wei()->isQQ('123456')) {
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
| patternMessage      | string  | %name%必须是有效的QQ号码               | -                 |
| negativeMessage     | string  | %name%不能是QQ号码                     | -                 |

### 方法

#### isQQ($input)
检查数据是否为有效的QQ号码
