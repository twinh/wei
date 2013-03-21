[isLength()](http://twinh.github.com/widget/api/isLength)
=========================================================

检查数据的长度是否为指定的数值,或在指定的长度范围内

##### 目录
* isLength ( $input [, $length ] )
* isLength ( $input [, $min [, $max ] ] )

### 检查数据的长度是否为指定的数值
```php
bool isLength ( $input [, $length ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$length** `int` 指定长度的值

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notDetectd`          | 无法检测到%name%的长度                                         |
| `length`              | %name%的长度必须是%length%                                     |
| `lengthItem`          | %name%必须包含%length%项                                       |

当检查的数据是字符串时,返回的错误信息是`length`,当数据是数组时,返回的是`lengthItem`

##### 代码范例
检查"abc"的长度是否为3
```php
<?php
 
if ($widget->isLength('abc', 3)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
- - - -

### 检查数据的长度是否在指定的长度范围内
```php
bool isLength ( $input [, $min [, $max ] ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$min** `int` 限制长度的最小值
* **$max** `int` 限制长度的最大值

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notDetectd`          | 无法检测到%name%的长度                                         |
| `notIn`               | %name%的长度必须在%min%和%max%之间                             |
| `notInItem`           | %name%必须包含%min%到%max%项                                   |

当检查的数据是字符串时,返回的错误信息是`notIn`,当数据是数组时,返回的是`notInItem`

##### 代码范例
检查"abc"的长度是否在3到6之间
```php
<?php
 
if ($widget->isLength('abc', 3, 6)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
