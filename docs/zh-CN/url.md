    该微件文档还在紧急编写中,敬请期待!
[url()](http://twinh.github.io/widget/api/url)
==============================================

Build URL by specified uri and parameters

### Build URL by specified uri and parameters
```php
string url($uri)
```

##### 参数
* **$uri** `string` The uri like "user/edit"
* **$parameters** `array|string` Additional URL query parameters
* **$_** `array|string` More additional URL query parameters


```php
// Returns controller=user&id=admin
$this->url('user', array('id' => 'admin'));

// Returns controller=user&action=edit&id=>admin
$this->url('user/edit', array('id' => 'admin'));

// Returns module=api&controller=user&action=edit&id=admin
$this->url('api/user/edit', array('id' => 'admin'))
```
