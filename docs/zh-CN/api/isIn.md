[isIn()](http://twinh.github.com/widget/api/isIn)
=================================================

检查数据是否在指定的数组中

### 
```php
bool isDir( $input [, $array [, $strict ] ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$array** `array` 用于搜索的数组
* **$strict** `bool` 是否使用全等(===)进行比较,默认使用等于(==)比较

##### 范例
检查"1"是否在array(1, 2, 3)之中
```php
<?php
 
if ($widget->isIn(1, array(1, 2, 3))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
