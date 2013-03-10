[isLength()](http://twinh.github.com/widget/api/isLength)
=========================================================

检查数据的长度是否为指定的数值,或在指定的长度范围内

##### 目录
* isLength ( $input [, $length ] )
* isLength ( $input [, $min [, $max ] ] )

### 
```php
bool isLength ( $input [, $length ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$length** `int` 指定长度的值

##### 范例
检查"abc"的长度是否为3
```php
<?php
 
if ($widget->isLength('abc', 3)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
- - - -

### 
```php
bool isLength ( $input [, $min [, $max ] ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$min** `int` 限制长度的最小值
* **$max** `int` 限制长度的最大值

##### 范例
检查"abc"的长度是否在3到6之间
```php
<?php
 
if ($widget->isLength('abc', 3, 6)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
