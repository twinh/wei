Memcache
========

设置或获取一项缓存,缓存数据存储于Memcache中

案例
----

### 设置和获取缓存
```php
// 设置缓存
widget()->memcache('key', 'value');
// 返回 true

// 获取缓存
widget()->memcache('key');
// 返回 value
```

### 设置60秒后就过期的缓存
```php
widget()->memcache('key', 'value', 60);
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
-------

### 选项

| 名称       | 类型         | 默认值                 | 说明                                                    |
|------------|--------------|------------------------|---------------------------------------------------------|
| servers    | array        | 见下表                 | 服务器配置数组                                          |
| flag       | int          | `MEMCACHE_COMPRESSED`  | 用于服务器验证的用户名                                  |
| object     | \Memcache    | 无                     | 原始的Memcache对象                                      |

#### 选项`servers`
| 名称       | 类型         | 默认值                 | 说明                                                    |
|------------|--------------|------------------------|---------------------------------------------------------|
| host       | string       | 127.0.0.1              | Memcache服务器地址                                      |
| port       | int          | 11211                  | Memcache服务器端口                                      |
| persistent | bool         | true                   | 是否使用长连接                                          |

### 方法

#### memcache($key, $value, $expire = 0)
设置缓存的值

##### 参数

| 名称      | 类型      | 默认值    | 说明                                  |
|-----------|-----------|-----------|---------------------------------------|
| $key      | string    | 无        | 缓存的键名                            |
| $value    | mixed     | 无        | 缓存的值,允许任意类型                 |
| $expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期   |

#### memcache($key)
获取指定名称的缓存

#### memcache->set($key, $value)
设置缓存的值,同`memcache($key, $value, $expire = 0)`

#### memcache->get($key)
获取缓存的值,同`memcache($key)`

#### memcache->remove($key)
移除一项缓存

#### memcache->exists($key)
检查缓存是否存在

#### memcache->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### memcache->replace($key, $value)
替换一项缓存,如果缓存不存在,返回false

#### memcache->increment($key, $offset = 1)
增大一项缓存的值

#### memcache->decrement($key, $offset = 1)
减小一项缓存的值

#### memcache->getMulti($keys)
批量获取缓存的值

#### memcache->setMulti($values)
批量设置缓存的值
