[inMethod()](http://twinh.github.com/widget/api/inMethod)
=========================================================

检查当前的请求方式是否为指定的字符串

### 
```php
bool inMethod()
```

##### 参数
* **$method** `string` 指定的请求方式名称


`inMethod`微件是`request`微件`%name%`方法的别名.


##### 代码范例
检查当前的请求方式是否为HEAD
```php
<?php

if ($widget->inMethod('HEAD')) {
    echo 'The request method is HEAD';
} else {
    echo 'The request method is not HEAD';
}
```
##### 运行结果
```php
'The request method is not HEAD'
```
