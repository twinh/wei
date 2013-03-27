[isRequired()](http://twinh.github.com/widget/api/isRequired)
=============================================================

检查数据是否为空

### 
```php
bool isRequired ( $input [, $required ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$required** `bool` true表示数据不可以为空,false表示数据可为空


用于组合验证,如果允许为空且数据为空,则不对数据进行剩余规则的校验

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `required`            | %name%不能为空                                                 |
| `notString`           | %name%必须是字符串                                             |
| `negative`            | %name%不合法                                                   |


##### 代码范例
检查null是否为空
```php
<?php

if ($widget->isRequired(null)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
##### 代码范例
检查null是否为空,第二个参数设为false
```php
<?php

if ($widget->isRequired(null, false)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
