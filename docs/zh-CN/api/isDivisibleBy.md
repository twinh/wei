[isDivisibleBy()](http://twinh.github.com/widget/api/isDivisibleBy)
===================================================================

检查数据是否能被指定的除数整除

### 
```php
bool isDivisibleBy( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据,被除数
* **$divisor** `int|float` 除数,可以是整数或小数


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notDivisible`        | %name%必须被%divisor%整除                                      |
| `negative`            | %name%不可以被%divisor%整除                                    |
| `notString`           | %name%必须是字符串                                             |


##### 代码范例
检查10能否被3整除
```php
<?php

if ($widget->isDivisibleBy(10, 3)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
