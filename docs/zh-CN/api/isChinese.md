[isChinese()](http://twinh.github.com/widget/api/isChinese)
===========================================================

检查数据是否只由汉字组成

### 
```php
bool isChinese( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

isChinese
##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `pattern`             | %name%只能由中文组成                                           |
| `negative`            | %name%不能只由中文组成                                         |
| `notString`           | %name%必须是字符串                                             |

##### 代码范例
检查数据是否只由汉字组成
```php
<?php

if ($widget->isChinese('中文')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
