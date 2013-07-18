MongoCache
==========

设置或获取一项缓存,缓存数据存储于[Mangodb](http://docs.mongodb.org/ecosystem/drivers/php/)中

案例
----

### 设置和获取缓存
```php
// 设置缓存
widget()->mongoCache('key', 'value');
// 返回 true

// 获取缓存
widget()->mongoCache('key');
// 返回 value
```

### 设置60秒后就过期的缓存
```php
widget()->mongoCache('key', 'value', 60);
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
-------

### 选项

| 名称       | 类型         | 默认值         | 说明                                                    |
|------------|--------------|----------------|---------------------------------------------------------|
| host       | string       | localhost      | Mongodb所在的服务器名称                                 |
| port       | int          | 27017          | Mongodb所在的服务器的端口                               |
| db         | string       | cache          | 存储缓存数据的数据库的名称                              |
| collection | string       | cache          | 存储缓存数据的集合的名称                                |

### 方法

#### mongoCache($key, $value, $expire = 0)
设置缓存的值

##### 参数

| 名称      | 类型      | 默认值    | 说明                                  |
|-----------|-----------|-----------|---------------------------------------|
| $key      | string    | 无        | 缓存的键名                            |
| $value    | mixed     | 无        | 缓存的值,允许任意类型                 |
| $expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期   |

#### mongoCache($key)
获取指定名称的缓存

#### mongoCache->set($key, $value, $expire = 0)
设置缓存的值,同`mongoCache($key, $value, $expire = 0)`

#### mongoCache->get($key)
获取缓存的值,同`mongoCache($key)`

#### mongoCache->remove($key)
移除一项缓存

#### mongoCache->exists($key)
检查缓存是否存在

#### mongoCache->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### mongoCache->replace($key, $value)
替换一项缓存,如果缓存不存在,返回false

#### mongoCache->increment($key, $offset = 1)
增大一项缓存的值

#### mongoCache->decrement($key, $offset = 1)
减小一项缓存的值

#### mongoCache->getMulti($keys)
批量获取缓存的值

#### mongoCache->setMulti($values)
批量设置缓存的值
