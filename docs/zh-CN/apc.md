Apc
===
通过面向对象的方式设置或获取PHP APC缓存,并提供更多友好方便的功能方法.

案例
----
### 设置和获取缓存
```php
// 设置缓存
$widget->apc('key', 'value');
// true

// 获取缓存
$widget->apc('key');
// value
```

### 设置60秒后就过期的缓存
```php
$widget->apc('key', 'value', 60);
```

调用方式
-------
### 选项
*无*

### 方法

#### apc ($key, $value)
设置缓存的值
```php
$widget->apc('key', 'value');
```

#### apc($key)
获取指定名称的缓存
```php
$widget->apc('name');
```

### apc->set($key, $value)
设置缓存的值


### apc->get($key)
获取缓存的值

### apc->remove($key)
移除一项缓存

### apc->exists($key)
检查缓存是否存在

### apc->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

### apc->replace($key, $value)
替换一项缓存,如果缓存 **不** 存在,返回false

### apc->increment($key, $offset = 1)
增大一项缓存的值

### apc->decrement($key, $offset = 1)
减小一项缓存的值

### apc->getMulti($keys)
批量获取缓存的值

### apc->setMulti($values)
批量设置缓存的值





