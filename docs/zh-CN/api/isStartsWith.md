[isStartsWith()](http://twinh.github.com/widget/api/isStartsWith)
=================================================================

检查数据是否以指定字符串开头

### 
```php
bool isStartsWith( $input [, $findMe [, $case ] ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$findMe** `string|array` 要查找的字符串,可以是字符串或数组.如果是数组,只要数据以数组中任何一个元素开头就算验证通过
* **$case** `bool` 是否区分大小写,默认为不区分


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notFound`            | %name%必须以%findMe%开头                                       |
| `negative`            | %name%不能以%findMe%开头                                       |
| `notString`           | %name%必须是字符串                                             |


##### 代码范例
检查"abc"是否以"C"开头
```php
<?php

if ($widget->isEndsWith('abc', 'C')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
##### 代码范例
以区分大小写的方式,检查"abc"是否以"C"开头
```php
<?php

if ($widget->isEndsWith('abc', 'C', true)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
##### 代码范例
检查"abc"是否以数组array('a', 'b', 'c')中的任意元素开头
```php
<?php

if ($widget->isEndsWith('abc', array('a', 'b', 'c'))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
