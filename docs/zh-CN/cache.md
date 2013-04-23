[cache()](http://twinh.github.io/widget/api/cache)
==================================================

设置或获取指定缓存类型的一项缓存

##### 目录
* cache( $key, $value [ $expire ] )
* cache( $key )

### 设置指定存储类型的一项缓存
```php
bool cache( $key, $value [ $expire ] )
```

##### 参数
* **$key** `string` 缓存的键名
* **$value** `mixed` 缓存的值,允许任意类型
* **$expire** `int` 缓存的有效期,默认为0秒,表示永不过期


该调用方式相等于`$widget->cache->set($key, $value, $expire)`

默认的缓存类型是apc,可以通过`$widget->cache->setDriver('redis')`来更改缓存为redis或其他缓存类型

使用`cache`微件会让你的代码更加灵活自由,你可以不用关注缓存的类型,并且可以根据需求或代码运行环境快速切换缓存类型,同时不用过多改动已有的代码

目前支持的缓存类型有
* `apc`
* `dbCache`
    * sqlite
    * mysql
    * pgsql
    * sqlsrv/dblib *不稳定*
    * oci *不稳定*
* `memcache`
* `memcached`
* `redis`


##### 代码范例
设置键名为"key",值为"value"的缓存
```php
<?php

$widget->cache('key', 'value');

echo $widget->cache('key');
```
##### 运行结果
```php
'value'
```
- - - -

### 获取指定缓存类型的一项缓存
```php
bool cache( $key )
```

##### 参数
* **$key** `string` 缓存的键名


该调用方式相等于`$widget->cache->get($key)`


##### 代码范例
获取键名为"key"的缓存,并打印出来
```php
<?php

$widget->cache('key', 'value');

echo $widget->cache('key');
```
##### 运行结果
```php
'value'
```
