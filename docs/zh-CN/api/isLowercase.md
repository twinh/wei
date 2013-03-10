[isLowercase()](http://twinh.github.com/widget/api/isLowercase)
===============================================================

检查数据是否为小写字符

### 
```php
bool isLowercase( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查"abc"是否为小写字符
```php
<?php
 
if ($widget->isLowercase('abc')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
