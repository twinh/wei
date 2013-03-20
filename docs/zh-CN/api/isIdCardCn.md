[isIdCardCn()](http://twinh.github.com/widget/api/isIdCardCn)
=============================================================

检查数据是否为有效的中国身份证

### 
```php
bool isIdCardCn( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 代码范例
检查15位数字"342622840209049"是否为有效的中国身份证
```php
<?php

if ($widget->isIdCardCn('342622840209049')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
