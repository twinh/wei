[isRange()](http://twinh.github.com/widget/api/isRange)
=======================================================

检查数据是否在指定的两个值之间

### 
```php
bool isRange( $input [, $min [, $max ] ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$min** `int` 限制的最小值
* **$max** `int` 限制的最大值


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `range`               | %name%必须在%min%到%max%之间                                   |
| `negative`            | %name%不能在%min%到%max%之间                                   |
| `notString`           | %name%必须是字符串                                             |


##### 代码范例
检查18是否在1到10之间
```php
<?php
 
if ($widget->isRange(18, 1, 10)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
