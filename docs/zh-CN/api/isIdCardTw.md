[isIdCardTw()](http://twinh.github.com/widget/api/isIdCardTw)
=============================================================

检查数据是否为有效的台湾身份证

### 
```php
bool isIdCardTw( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 代码范例
检查"A122501945"是否为有效的台湾身份证
```php
<?php

if ($widget->isIdCardTw('A122501945')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
