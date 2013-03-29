[trigger()](http://twinh.github.com/widget/api/trigger)
=======================================================

触发指定类型的事件

### 
```php
\Widget\Event\EventInterface trigger( $type, [, $params [, $widget ] ] )
```

##### 参数
* **$type** `string|\Widget\Event\EventInterface` 事件的类型或是事件对象
* **$params** `array` 传递给事件触发器的参数
* **$widget** `\Widget\WidgetInterface` 微件对象


第三个参数`$widget`为可选的微件对象,如果微件包含与事件名称相同的属性,那么,事件管理器将会把该属性
作为一个事件触发器,在所有的触发器触发后才触发.


##### 代码范例
添加一个名称为example的事件并触发它
```php
<?php

$widget->on('example', function(){
    echo 'example';
});

$widget->trigger('example');
```
##### 运行结果
```php
'example'
```
