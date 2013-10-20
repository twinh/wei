isPhoneCn
=========

检查数据是否为有效的电话号码

案例
----

### 检查"020-1234567"是否为电话号码
```php
if (wei()->isPhoneCn('020-1234567')) {
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
| patternMessage      | string  | %name%必须是有效的电话号码                  | -                 |
| negativeMessage     | string  | %name%不能是电话号码                        | -                 |

### 方法

#### isPhoneCn($input)
检查数据是否为有效的电话号码

相关链接
--------

* [验证器概览](../book/validators.md)