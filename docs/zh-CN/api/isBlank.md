[isBlank()](http://twinh.github.com/widget/api/isBlank)
=======================================================

检查数据是否为空(不允许空格)

### 
```php
bool isBlank( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `blank`               | %name%必须为空                                                 |
| `negative`            | %name%不能为空                                                 |

##### 代码范例
检查空白字符会返回成功
```php
<?php

$input = '    ';
if ($widget->isBlank($input)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
