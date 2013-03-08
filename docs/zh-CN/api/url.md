[url()](http://twinh.github.com/widget/api/url)
===============================================

快速构建链接

##### 目录
* url($value1, $value2, $params)

### 快速构建链接
```php
string url($value1, $value2, $params)
```

##### 参数
* **$value1** `string` 值1
* **$value2** `string` 值2
* **$params** `array` 其他参数


大多数的应用,都是通过两个参数定位请求,
例如Widget使用的是的模块(module)和操作(action)两个参数,
其他一些程序可能是使用控制器(controller)和操作(action),
或是模块(mod,m)和操作(act,a)的缩写等等
通过该方法,可以减少编写链接的工作量,同时将参数名称隐藏起来
