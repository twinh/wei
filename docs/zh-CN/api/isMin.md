[isMin()](http://twinh.github.com/widget/api/isMin)
===================================================

检查数据是否大于等于指定的值

### 
```php
bool isMin ( $input [, $min ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$min** `int` 待比较的数值

##### 范例
检查10是否大于等于20
```php
<?php
 
if ($widget->isMin(10, 20)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'No'
```
