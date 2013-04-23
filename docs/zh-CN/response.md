[response()](http://twinh.github.io/widget/api/response)
========================================================

发送HTTP响应头和响应内容

### 
```php
\Widget\Response response( $content [, $stateCode ] )
```

##### 参数
* **$content** `string` 响应的内容
* **$stateCode** `string` HTTP状态码,默认是`200`

##### 代码范例
发送内容为"Hello World"的HTTP响应
```php
<?php

$widget->response('Hello World');
```
##### 运行结果
```php
'Hello World'
```
##### 代码范例
查看上面例子中HTTP响应的完整内容
```php
<?php

echo (string)$widget->response;
```
##### 运行结果
```php
'HTTP/1.1 200 OK

Hello World'
```
