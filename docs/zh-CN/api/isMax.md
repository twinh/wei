[isMax()](http://twinh.github.com/widget/api/isMax)
===================================================

检查数据是否小于等于指定的值

### 
```php
bool isMax ( $input [, $max ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$max** `int` 待比较的数值

##### 代码范例
检查10是否小于等于20
```php
<?php
 
if ($widget->isMax(10, 20)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
