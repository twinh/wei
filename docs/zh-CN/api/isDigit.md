[isDigit()](http://twinh.github.com/widget/api/isDigit)
=======================================================

检查数据是否只由数字组成

### 
```php
bool isDigit( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `pattern`             | %name%只能由数字(0-9)组成                                      |
| `negative`            | %name%不能只由数字(0-9)组成                                    |
| `notString`           | %name%必须是字符串                                             |


##### 代码范例
检查"abc123"是否为数字
```php
<?php

if ($widget->isDigit('abc123')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
