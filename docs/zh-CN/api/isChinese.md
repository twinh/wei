[isChinese()](http://twinh.github.com/widget/api/isChinese)
===========================================================

检查数据是否只由汉字组成

### 
```php
bool isChinese( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查数据是否只由汉字组成
```php
<?php

if ($widget->isChinese('中文')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
