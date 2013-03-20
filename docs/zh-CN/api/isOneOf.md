[isOneOf()](http://twinh.github.com/widget/api/isOneOf)
=======================================================

检查数据是否满足指定规则中的任何一条

### 
```php
bool isOneOf ( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 代码范例
检查"abc"是否为数字且最大长度不超过2
```php
<?php

$rules = array(
    'digit' => true,
    'maxLength' => 2
);
if ($widget->isOneOf('abc', $rules)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
