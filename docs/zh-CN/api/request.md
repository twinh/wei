[request()](http://twinh.github.io/widget/api/request)
======================================================

获取一项HTTP请求参数($_REQUEST)的值

### 
```php
string|null request( $name [, $default ] )
```

##### 参数
* **$name** `string` 请求参数的名称
* **$default** `string` 当请求参数不存在时返回的值,默认是null


**注意:**通过`$widget->request($name)`取到的值是字符串或`$default`,如果需要获取原始值,可以通过`$widget->request->getRaw($name)`取得.

