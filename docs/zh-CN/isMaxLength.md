isMaxLength
===========

检查数据的长度是否小于等于指定数值

案例
----

### 检查"abc"的长度是否小于等于2
```php
if (widget()->isMaxLength('abc', 2)) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'No'
```

### 选项

| 名称              | 类型    | 默认值                             | 说明                 |
|-------------------|---------|------------------------------------|----------------------|
| max               | int     | 无                                 | 待比较的数值         |
| notDetectdMessage | string  | 无法检测到%name%的长度             | -                    |
| tooLongMessage    | string  | %name%的长度必须小于等于%max%      | 当数据为字符串时出现 |
| tooManayMessage   | string  | %name%最多包含%max%项              | 当数据为数组时出现   |
| negativeMessage   | string  | %name%不合法                       | -                    |

### 方法

#### isMaxLength($input, $max)
检查数据的长度是否小于等于指定数值
