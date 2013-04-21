    该微件文档还在紧急编写中,敬请期待!
[error()](http://twinh.github.io/widget/api/error)
==================================================

Attach a handler to the error event

### Attach a handler to the error event
```php
\EventManager error($fn, $priority, $data)
```

##### 参数
* **$fn** `\Closure` The error handler
* **$priority** `int|string` The event priority, could be int or specify strings, the higer number, the higer priority
* **$data** `array` The data pass to the event object, when the handler is triggered

