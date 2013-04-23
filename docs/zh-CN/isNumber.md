[isNumber()](http://twinh.github.io/widget/api/isNumber)
========================================================

检查数据是否为有效数字

### 
```php
bool isNumber( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notNumber`           | %name%必须是有效的数字                                         |
| `negative`            | %name%不能是数字                                               |

##### 代码范例
检查-123.4是否为数字
```php
<?php
 
if ($widget->isNumber(-123.4)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
