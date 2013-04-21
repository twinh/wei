[inGet()](http://twinh.github.io/widget/api/inGet)
==================================================

检查当前的请求方式是否为GET

### 
```php
bool inGet()
```

##### 参数
*无*


`inGet`微件是`request`微件`inGet`方法的别名.


##### 代码范例
检查当前的请求方式是否为GET
```php
<?php

if ($widget->inGet()) {
    echo 'The request method is GET';
} else {
    echo 'The request method is not GET';
}
```
##### 运行结果
```php
'The request method is GET'
```
