DbCache
=======

设置或获取一项缓存,缓存数据存储于数据库中

dbCache微件依赖于<a href="#db">db</a>微件

案例
----

### 设置和获取缓存
```php
// 设置缓存
widget()->dbCache('key', 'value');
// 返回 true

// 获取缓存
widget()->dbCache('key');
// 返回 value
```

### 设置60秒后就过期的缓存
```php
widget()->dbCache('key', 'value', 60);
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
--------

### 选项

| 名称      | 类型      | 默认值    | 说明               |
|-----------|-----------|-----------|--------------------|
| table     | string    | cache     | 缓存数据表名称     |

### 方法

#### dbCache($key, $value, $expire = 0)
设置缓存的值

##### 参数

| 名称      | 类型      | 默认值    | 说明                                  |
|-----------|-----------|-----------|---------------------------------------|
| $key      | string    | 无        | 缓存的键名                            |
| $value    | mixed     | 无        | 缓存的值,允许任意类型                 |
| $expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期   |

#### dbCache($key)
获取指定名称的缓存

#### dbCache->set($key, $value, $expire = 0)
设置缓存的值,同`dbCache($key, $value, $expire = 0)`

#### dbCache->get($key)
获取缓存的值,同`dbCache($key)`

#### dbCache->remove($key)
移除一项缓存

#### dbCache->exists($key)
检查缓存是否存在

#### dbCache->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### dbCache->replace($key, $value)
替换一项缓存,如果缓存不存在,返回false

#### dbCache->inc($key, $offset = 1)
增大一项缓存的值

#### dbCache->dec($key, $offset = 1)
减小一项缓存的值

#### dbCache->getMulti($keys)
批量获取缓存的值

#### dbCache->setMulti($values)
批量设置缓存的值
