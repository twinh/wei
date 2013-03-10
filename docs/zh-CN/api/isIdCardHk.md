[isIdCardHk()](http://twinh.github.com/widget/api/isIdCardHk)
=============================================================

检查数据是否为有效的香港身份证

### 
```php
bool isIdCardHk( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查"Z437626A"是否为有效的香港身份证
```php
<?php

if ($widget->isIdCardHk('Z437626A')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
