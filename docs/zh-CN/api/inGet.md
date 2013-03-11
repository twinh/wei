[inGet()](http://twinh.github.com/widget/api/inGet)
===================================================

检查当前的请求方式是否为GET

### 
```php
bool inGet()
```

##### 参数
*无*


`inGet`微件是`request`微件`inGet`方法的别名.


##### 范例
检查当前的请求方式是否为GET
```php
<?php

if ($widget->inGet()) {
    echo 'The request method is GET';
} else {
    echo 'The request method is not GET';
}
```
##### 输出
```php
'The request method is GET'
```
