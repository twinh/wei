[isIdCardHk()](http://twinh.github.io/widget/api/isIdCardHk)
============================================================

检查数据是否为有效的香港身份证

### 
```php
bool isIdCardHk( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `invalid`             | %name%必须是有效的香港身份证                                   |
| `negative`            | %name%不能是有效的香港身份证                                   |
| `notString`           | %name%必须是字符串                                             |

##### 代码范例
检查"Z437626A"是否为有效的香港身份证
```php
<?php

if ($widget->isIdCardHk('Z437626A')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
