[isIdCardMo()](http://twinh.github.com/widget/api/isIdCardMo)
=============================================================

检查数据是否为有效的澳门身份证

### 
```php
bool isIdCardMo( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `pattern`             | %name%必须是有效的澳门身份证                                   |
| `negative`            | %name%不能是有效的澳门身份证                                   |
| `notString`           | %name%必须是字符串                                             |

##### 代码范例
检查"11111111"是否为有效的澳门身份证
```php
<?php

if ($widget->isIdCardMo('11111111')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
