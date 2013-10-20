isLength
========

检查数据的长度是否为指定的数值,或在指定的长度范围内

案例
----

### 检查"abc"的长度是否为3
```php
if (wei()->isLength('abc', 3)) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'Yes'
```

### 检查"abc"的长度是否在3到6之间
```php
if (wei()->isLength('abc', 3, 6)) {
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

*见方法*

### 方法

#### isLength($input, $min, $max)
检查数据的长度是否在指定的长度范围内

##### 选项

| 名称              | 类型    | 默认值                             | 说明                 |
|-------------------|---------|------------------------------------|----------------------|
| min               | int     | 无                                 | 限制长度的最小值     |
| max               | int     | 无                                 | 限制长度的最大值     |
| notDetectdMessage | string  | 无法检测到%name%的长度             | -                    |
| notInMessage      | string  | %name%的长度必须在%min%和%max%之间 | -                    |
| notInItemMessage  | string  | %name%必须包含%min%到%max%项       | -                    |

    注意: 当检查的数据是字符串时,返回的错误信息是`notIn`,当数据是数组时,返回的是`notInItem`


#### isLength($input, $length)
检查数据的长度是否为指定的数值

##### 选项

| 名称              | 类型    | 默认值                             | 说明                 |
|-------------------|---------|------------------------------------|----------------------|
| length            | int     | 无                                 | 指定长度的值         |
| notDetectdMessage | string  | 无法检测到%name%的长度             | -                    |
| lengthMessage     | string  | %name%的长度必须是%length%         | -                    |
| lengthItemMessage | string  | %name%必须包含%length%项           | -                    |

    注意: 当检查的数据是字符串时,返回的错误信息是`length`,当数据是数组时,返回的是`lengthItem`

相关链接
--------

* [验证器概览](../book/validators.md)