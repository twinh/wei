[isMaxLength()](http://twinh.github.com/widget/api/isMaxLength)
===============================================================

检查数据的长度是否小于等于指定数值

### 
```php
bool isMaxLength( $input [, $max ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$max** `int` 待比较的数值

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `tooLong`             | %name%的长度必须小于等于%max%                                  |
| `tooManay`            | %name%最多包含%max%项                                          |
| `notDetectd`          | 无法检测到%name%的长度                                         |
| `notString`           | %name%必须是字符串                                             |
| `negative`            | %name%不合法                                                   |

##### 代码范例
检查"abc"的长度是否小于等于2
```php
<?php
 
if ($widget->isMaxLength('abc', 2)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
