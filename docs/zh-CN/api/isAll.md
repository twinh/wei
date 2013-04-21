[isAll()](http://twinh.github.io/widget/api/isAll)
==================================================

检查数组或对象里的每一项是否符合指定的规则

### 
```php
bool isAll( $input [, $rules ] )
```

##### 参数
* **$input** `array|\Traversable` 待验证的数据
* **$rules** `array` 验证规则数组,数组的键名是规则名称,数组的值是验证规则的配置选项

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notArray`            | %name%必须是数组                                               |

##### 代码范例
检查数组里的每一项是否都为数字
```php
<?php

$input = array(3, 2, 5);
if ($widget->isAll($input, array(
    'digit' => true
))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
