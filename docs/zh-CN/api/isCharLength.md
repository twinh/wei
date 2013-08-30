isCharLength
============

检查数据的 **字符数** 是否为指定的数值

案例
----

### 检查"微框架"的长度是否为3

```php
if (widget()->isCharLength('微框架', 3)) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'Yes'
```

### 检查"微框架"的长度是否在3到6之间

```php
if (widget()->isCharLength('微框架', 3, 6)) {
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

名称              | 类型    | 默认值  | 说明
------------------|---------|---------|------
charset           | string  | UTF-8   | 验证数据的字符集

### 方法

#### isCharLength($input, $min, $max)

检查数据的字符数是否在指定的长度范围内

##### 选项

| 名称              | 类型    | 默认值                             | 说明                 |
|-------------------|---------|------------------------------------|----------------------|
| min               | int     | 无                                 | 限制长度的最小值     |
| max               | int     | 无                                 | 限制长度的最大值     |
| notDetectdMessage | string  | 无法检测到%name%的长度             | -                    |
| notInMessage      | string  | %name%的长度必须在%min%和%max%之间 | -                    |

#### isCharLength($input, $length)

检查数据的字符数是否为指定的数值

##### 选项

| 名称              | 类型    | 默认值                             | 说明                 |
|-------------------|---------|------------------------------------|----------------------|
| length            | int     | 无                                 | 指定长度的值         |
| notDetectdMessage | string  | 无法检测到%name%的长度             | -                    |
| lengthMessage     | string  | %name%的长度必须是%length%         | -                    |