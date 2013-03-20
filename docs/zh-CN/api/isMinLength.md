[isMinLength()](http://twinh.github.com/widget/api/isMinLength)
===============================================================

检查数据的长度是否大于等于指定数值

### 
```php
bool isMinLength ( $input [, $min ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$min** `int` 待比较的数值

##### 代码范例
检查"abc"的长度是否大于等于2
```php
<?php
 
if ($widget->isMinLength('abc', 2)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
