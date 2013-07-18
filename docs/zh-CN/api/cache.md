Cache
=====

设置或获取指定缓存类型的一项缓存

使用`cache`微件会让你的代码更加灵活自由,你可以不用关注缓存的类型,并且可以根据需求或代码运行环境快速切换缓存类型,同时不用过多改动已有的代码

案例
----

### 设置和获取缓存
```php
// 设置缓存
widget()->cache('key', 'value');
// 返回 true

// 获取缓存
widget()->cache('key');
// 返回 value
```

### 设置60秒后就过期的缓存
```php
widget()->cache('key', 'value', 60);
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
-------

### 选项

| 名称      | 类型   | 默认值    | 说明                                                            |
|-----------|--------|-----------|-----------------------------------------------------------------|
| driver    | string | apc       | 缓存的类型                                                      |

目前支持的缓存类型有

* `apc` APC缓存 *推荐*
* `arrayCache` 数值缓存
* `couchbase` Couchbase缓存
* `dbCache` 数据库缓存
* `fileCache` 文件缓存
* `memcache` Memcache缓存
* `memcached` Memcached缓存
* `redis` Redis缓存 *推荐*

### 方法

#### cache($key, $value, $expire = 0)
设置缓存的值

##### 参数

| 名称      | 类型      | 默认值    | 说明                                  |
|-----------|-----------|-----------|---------------------------------------|
| $key      | string    | 无        | 缓存的键名                            |
| $value    | mixed     | 无        | 缓存的值,允许任意类型                 |
| $expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期   |

#### cache($key)
获取指定名称的缓存

#### cache->set($key, $value, $expire = 0)
设置缓存的值,同`cache($key, $value, $expire = 0)`

#### cache->get($key)
获取缓存的值,同`cache($key)`

#### cache->remove($key)
移除一项缓存

#### cache->exists($key)
检查缓存是否存在

#### cache->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### cache->replace($key, $value)
替换一项缓存,如果缓存不存在,返回false

#### cache->increment($key, $offset = 1)
增大一项缓存的值

#### cache->decrement($key, $offset = 1)
减小一项缓存的值

#### cache->getMulti($keys)
批量获取缓存的值

#### cache->setMulti($values)
批量设置缓存的值
