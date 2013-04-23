[on()](http://twinh.github.io/widget/api/on)
============================================

添加指定类型的事件触发器

### 
```php
\Widget\EventManager on( $type, $fn [, $priority [, $data ] ] )
```

##### 参数
* **$type** `string` 事件的类型
* **$fn** `callback` 事件触发器
* **$priority** `int|string` 事件触发器的优先级,越大表示优先级越高,默认是0
* **$data** `array` 当事件触发器触发时,传递给事件对象的数据


事件触发器的优先级既可以是数字,也可以是字符串.预定于的字符串有`low`, `normal`, `high`,它们分别代表的数值如下表所示:

| **值**   | **数值** |
|----------|----------|
| `low`    | -1000    |
| `normal` | 0        |
| `high`   | 1000     |



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
