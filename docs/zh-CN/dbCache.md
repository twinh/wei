DbCache
=======

设置或获取一项缓存,缓存数据存储于数据库中

目前支持`sqlite`,`mysql`,`pgsql`,`sqlsrv`/`dblib`(不稳定),`oci`(不稳定)

案例
----

### 设置和获取缓存
```php
// 设置缓存
$widget->dbCache('key', 'value');
// 返回 true

// 获取缓存
$widget->dbCache('key');
// 返回 value
```

### 设置60秒后就过期的缓存
```php
$widget->dbCache('key', 'value', 60);
```

调用方式
-------

### 选项

*无*

### 方法

#### dbCache( $key, $value [ $expire ] )
设置缓存的值
##### 参数
* **$key** `string` 缓存的键名
* **$value** `mixed` 缓存的值,允许任意类型
* **$expire** `int` 缓存的有效期,默认为0秒,表示永不过期

#### dbCache( $key )
获取指定名称的缓存

### dbCache->set($key, $value)
设置缓存的值

### dbCache->get($key)
获取缓存的值

### dbCache->remove($key)
移除一项缓存

### dbCache->exists($key)
检查缓存是否存在

### dbCache->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

### dbCache->replace($key, $value)
替换一项缓存,如果缓存 **不** 存在,返回false

### dbCache->increment($key, $offset = 1)
增大一项缓存的值

### dbCache->decrement($key, $offset = 1)
减小一项缓存的值

### dbCache->getMulti($keys)
批量获取缓存的值

### dbCache->setMulti($values)
批量设置缓存的值