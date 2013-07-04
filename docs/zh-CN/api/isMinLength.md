isMinLength
===========

检查数据的长度是否大于等于指定数值

案例
----

### 检查"abc"的长度是否大于等于2
```php
if (widget()->isMinLength('abc', 2)) {
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

| 名称              | 类型    | 默认值                             | 说明                 |
|-------------------|---------|------------------------------------|----------------------|
| max               | int     | 无                                 | 待比较的数值         |
| notDetectdMessage | string  | 无法检测到%name%的长度             | -                    |
| tooShortMessage   | string  | %name%的长度必须大于等于%min%      | 当数据为字符串时出现 |
| tooFewMessage     | string  | %name%至少需要包括%min%项          | 当数据为数组时出现   |
| negativeMessage   | string  | %name%不合法                       | -                    |

### 方法

#### isMinLength($input, $min)
检查数据的长度是否大于等于指定数值
