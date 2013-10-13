isPostcodeCn
============

检查数据是否为有效的中国邮政编码

案例
----

### 检查"123456"是否为有效的中国邮政编码
```php
if (wei()->isPostcodeCn('123456')) {
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

| 名称                | 类型    | 默认值                           | 说明              |
|---------------------|---------|----------------------------------|-------------------|
| notStringMessage    | string  | %name%必须是字符串               | -                 |
| patternMessage      | string  | %name%必须是6位长度的数字        | -                 |
| negativeMessage     | string  | %name%不能是邮政编码             | -                 |

### 方法

#### isPostcodeCn($input)
检查数据是否为有效的中国邮政编码
