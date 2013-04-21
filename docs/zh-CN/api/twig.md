    该微件文档还在紧急编写中,敬请期待!
[twig()](http://twinh.github.io/widget/api/twig)
================================================

Returns \Twig_Environment object or render a template

### Returns \Twig_Environment object or render a template
```php
\Twig_Environment|string twig($name, $vars)
```

##### 参数
* **$name** `string` The name of template
* **$vars** `array` The variables pass to template


if NO parameter provied, the invoke method will return the
\Twig_Environment object. otherwise, call the render method
