[isNumber()](http://twinh.github.com/widget/api/isNumber)
=========================================================

检查数据是否为有效数字

### 
```php
bool isNumber ( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查-123.4是否为数字
```php
<?php
 
if ($widget->isNumber(-123.4)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
