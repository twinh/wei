[isIn()](http://twinh.github.io/widget/api/isIn)
================================================

检查数据是否在指定的数组中

### 
```php
bool isIn( $input [, $array [, $strict ] ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$array** `array` 用于搜索的数组
* **$strict** `bool` 是否使用全等(===)进行比较,默认使用等于(==)比较

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notIn`               | %name%必须在指定的数据中:%array%                               |
| `negative`            | %name%不能在指定的数据中:%array%                               |

##### 代码范例
检查"1"是否在array(1, 2, 3)之中
```php
<?php
 
if ($widget->isIn(1, array(1, 2, 3))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
