[inPost()](http://twinh.github.com/widget/api/inPost)
=====================================================

检查当前的请求方式是否为POST

### 
```php
bool inPost()
```

##### 参数
*无*


`inPost`微件是`request`微件`inPost`方法的别名.


##### 范例
检查当前的请求方式是否为POST
```php
<?php

if ($widget->inGet()) {
    echo 'The request method is POST';
} else {
    echo 'The request method is not POST';
}
```
##### 输出
```php
'The request method is POST'
```
