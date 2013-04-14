    该微件文档还在紧急编写中,敬请期待!
[smarty()](http://twinh.github.com/widget/api/smarty)
=====================================================

Returns \Smarty object or render a template

### Returns \Smarty object or render a template
```php
\Smarty|string smarty($name, $vars)
```

##### 参数
* **$name** `string` The name of template
* **$vars** `array` The variables pass to template


if NO parameter provied, the invoke method will return the \Smarty
object otherwise, call the render method
