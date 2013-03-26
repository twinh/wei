[isNoneOf()](http://twinh.github.com/widget/api/isNoneOf)
=========================================================

检查数据是否不符合所有指定的规则校验

### 
```php
bool isNoneOf( $input [, $rules ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$rules** `array` 验证规则数组,数组的键名是规则名称,数组的值是验证规则的配置选项

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `invalid`             | %name%必须满足以下所有规则                                     |
| `notString`           | %name%必须是字符串                                             |
| `negative`            | %name%不合法                                                   |

##### 代码范例
检查数据不为数字,且长度不大于3
```php
<?php

$rules = array(
    'alpha' => true,
    'maxLength' => 3
);
if ($widget->isNoneOf('abc', $rules)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
