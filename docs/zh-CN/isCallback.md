[isCallback()](http://twinh.github.io/widget/api/isCallback)
============================================================

检查数据是否通过指定回调方法验证

### 
```php
bool isCallback( $input [, $fn [, $message ]] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$fn** `callback` 指定验证的回调结构
* **$message** `string` 验证不通过的提示信息

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `invalid`             | %name%不合法                                                   |

##### 代码范例
通过回调方法检查数据是否能被3整除
```php
<?php

if ($widget->isCallback(3, function($input) {
    return 0 === 10 % $input;
})) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
