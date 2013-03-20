[inAjax()](http://twinh.github.com/widget/api/inAjax)
=====================================================

检查当前的请求是否为Ajax(XMLHttpRequest)请求

### 
```php
bool inAjax()
```

##### 参数
*无*


`inAjax`微件是`request`微件`inAjax`方法的别名.


##### 代码范例
通过向server微件设置Ajax请求的标记,将当前请求伪装为Ajax请求
```php
<?php

unset($widget->server['HTTP_X_REQUESTED_WITH']);

if ($widget->inAjax()) {
    echo 'ajax request';
} else {
    echo 'not ajax request';
}

echo "\n";

$widget->server['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';

if ($widget->inAjax()) {
    echo 'ajax request';
} else {
    echo 'not ajax request';
}
```
##### 运行结果
```php
'not ajax request
ajax request'
```
