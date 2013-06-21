isColor
=======

检查数据是否为有效的十六进制颜色

案例
----

### 检查"#FF0000"是否为有效的十六进制颜色
```php
if (widget()->isColor('#FF0000')) {
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

| 名称                | 类型    | 默认值                                      | 说明              |
|---------------------|---------|---------------------------------------------|-------------------|
| notStringMessage    | string  | %name%必须是字符串                          | -                 |
| patternMessage      | string  | %name%必须是有效的十六进制颜色,例如#FF0000  | -                 |
| negativeMessage     | string  | %name%不能是有效的十六进制颜色              | -                 |

### 方法

#### isColor($input)
检查数据是否为有效的十六进制颜色
