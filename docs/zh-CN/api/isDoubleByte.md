[isDoubleByte()](http://twinh.github.io/widget/api/isDoubleByte)
================================================================

检查数据是否只由双字节字符组成

### 
```php
bool isDoubleByte( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `pattern`             | %name%只能由双字节字符组成                                     |
| `negative`            | %name%不能只由双字节字符组成                                   |
| `notString`           | %name%必须是字符串                                             |


##### 代码范例
检查"中文abc"是否只由双字节字符组成
```php
<?php

if ($widget->isDoubleByte('中文abc')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
