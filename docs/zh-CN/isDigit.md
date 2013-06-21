isDigit
=======

检查数据是否只由数字组成

案例
----

### 检查"abc123"是否为数字
```php
if (widget()->isDigit('abc123')) {
    echo 'Yes';
} else {
    echo 'No';
}
// 输出了'No'
```

调用方式
--------

### 选项

名称            | 类型   | 默认值                      | 说明
----------------|--------|-----------------------------|------
patternMessage  | string | %name%只能由数字(0-9)组成   |
negativeMessage | string | %name%不能只由数字(0-9)组成 |
notStringSring  | string | %name%必须是字符串          |

### 方法

#### isDigit($input)
检查数据是否只由数字组成
