[isCallback()](http://twinh.github.com/widget/api/isCallback)
=============================================================

检查数据是否通过指定回调方法验证

##### 目录
* isCallback( $input [, $fn [, $message ]] )

### 检查数据是否通过指定回调方法验证
```php
bool isCallback( $input [, $fn [, $message ]] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$fn** `callback` 指定验证的回调结构
* **$message** `string` 验证不通过的提示信息

##### 范例
```php
<?php

if ($widget->isCallback(3, function($input) {
    return 0 === 10 % $input;
})) {
    echo 'success';
} else {
    echo 'failure';
}
```
##### 输出
```php
'failure'
```
