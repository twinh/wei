Memcached
=========

设置或获取一项缓存,缓存数据存储于memcached中

案例
----

### 设置和获取缓存
```php
// 设置缓存
widget()->memcached('key', 'value');
// 返回 true

// 获取缓存
widget()->memcached('key');
// 返回 value
```

### 设置60秒后就过期的缓存
```php
widget()->memcached('key', 'value', 60);
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
-------

### 选项

| 名称       | 类型         | 默认值                 | 说明                                                    |
|------------|--------------|------------------------|---------------------------------------------------------|
| servers    | array        | 见下表                 | 服务器配置数组                                          |
| object     | \Memcached   | 无                     | 原始的memcached对象                                     |

#### 选项`servers`
| 名称       | 类型         | 默认值                 | 说明                                                    |
|------------|--------------|------------------------|---------------------------------------------------------|
| host       | string       | 127.0.0.1              | memcached服务器地址                                     |
| port       | int          | 11211                  | memcached服务器端口                                     |

### 方法

#### memcached($key, $value, $expire = 0)
设置缓存的值

##### 参数

| 名称      | 类型      | 默认值    | 说明                                  |
|-----------|-----------|-----------|---------------------------------------|
| $key      | string    | 无        | 缓存的键名                            |
| $value    | mixed     | 无        | 缓存的值,允许任意类型                 |
| $expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期   |

#### memcached($key)
获取指定名称的缓存

#### memcached->set($key, $value)
设置缓存的值,同`memcached($key, $value, $expire = 0)`

#### memcached->get($key)
获取缓存的值,同`memcached($key)`

#### memcached->remove($key)
移除一项缓存

##### memcached->exists($key)
检查缓存是否存在

##### memcached->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### memcached->replace($key, $value)
替换一项缓存,如果缓存不存在,返回false

#### memcached->inc($key, $offset = 1)
增大一项缓存的值

#### memcached->dec($key, $offset = 1)
减小一项缓存的值

#### memcached->getMulti($keys)
批量获取缓存的值

#### memcached->setMulti($values)
批量设置缓存的值
