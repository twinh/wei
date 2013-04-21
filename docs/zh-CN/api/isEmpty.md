[isEmpty()](http://twinh.github.io/widget/api/isEmpty)
======================================================

检查数据是否为空(允许空格)

### 
```php
bool isEmpty( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `empty`               | %name%必须为空                                                 |
| `negative`            | %name%不能为空                                                 |

##### 代码范例
检查"abc"是否为空
```php
<?php

if ($widget->isEmpty('abc')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
