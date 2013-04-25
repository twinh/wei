Event
=====

绑定,触发和移除事件,并支持命名空间等特性.

案例
----

### 绑定,触发和移除事件
```php
// 绑定名称为connect的事件
$widget->on('connect', function(){
    echo 'connected';
});

// 触发connect事件,将输出connected
$widget->trigger('connect');

// 移除connect事件
$widget->off('connect');

// 再次触发connect事件,没有任何输出
$widget->trigger('connect');
```

调用方式
--------

### 选项

*无*

### 方法

on
--
#### on($type, $fn, $priority = 0, $data = array())
绑定指定类型的事件触发器

##### 参数
* **$type** `string` 事件的类型
* **$fn** `callback` 事件触发器
* **$priority** `int|string` 事件触发器的优先级,越大表示优先级越高,默认是0
* **$data** `array` 当事件触发器触发时,传递给事件对象的数据


事件触发器的优先级既可以是数字,也可以是字符串.预定于的字符串有`low`, `normal`, `high`,它们分别代表的数值如下表所示:

| 值       | 数值     |
|----------|----------|
| `low`    | -1000    |
| `normal` | 0        |
| `high`   | 1000     |

off
---
####  off($type)
移除指定类型的事件触发器

##### 参数
* **$type** `string` 事件类型,可以包含命名空间,如"error.namespace",也可以只有命名空间,如".namespace"

`off`微件是事件管理器(`eventManager`)`remove`方法的别名

如果`$type`只是纯命名空间,如'.namespace',表示移除该命名空间下所有的事件触发器

trigger
------
### trigger($type, $params = array(), $widget = null)
触发指定类型的事件

##### 参数
* **$type** `string|\Widget\Event\EventInterface` 事件的类型或是事件对象
* **$params** `array` 传递给事件触发器的参数
* **$widget** `\Widget\WidgetInterface` 微件对象


第三个参数`$widget`为可选的微件对象,如果微件包含与事件名称相同的属性,那么,事件管理器将会把该属性
作为一个事件触发器,在所有的触发器触发后才触发.
