[off()](http://twinh.github.com/widget/api/off)
===============================================

移除指定类型的事件触发器

### 
```php
bool off( $type )
```

##### 参数
* **$type** `string` 事件类型,可以包含命名空间,如"error.namespace",也可以只有命名空间,如".namespace"


`off`微件是事件管理器(`eventManager`)`remove`方法的别名

如果`$type`只是纯命名空间,如'.namespace',表示移除该命名空间下所有的事件触发器


##### 代码范例
移除名称为example的事件触发器
```php
<?php

$widget->on('example', function(){
    echo 'example';
});

// Output 'example'
$widget->trigger('example');

$widget->off('example');

// Nothing ouput
$widget->trigger('example');
```
##### 运行结果
```php
'example'
```
