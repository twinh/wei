[isAllOf()](http://twinh.github.com/widget/api/isAllOf)
=======================================================

检查数组或对象里的每一项是否符合指定的规则

##### 目录
* isAll( $input [, $rules ] )

### 检查数据是否符合所有指定的规则
```php
bool isAll( $input [, $rules ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$rules** `array` 验证规则数组,数组的键名是规则名称,数组的值是验证规则的配置选项

##### 范例
```php
<?php

$input = '123456';
if ($widget->isAllOf($input, array(
	'length' => array(5, 10),
	'digit' => true
))) {
    echo 'success';
} else {
    echo 'failure';
}
```
##### 输出
```php
'success'
```
