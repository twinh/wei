Couchbase
=========

设置或获取一项缓存,缓存数据存储于Couchbase中

案例
----

### 设置和获取缓存
```php
// 设置缓存
widget()->couchbase('key', 'value');
// 返回 true

// 获取缓存
widget()->couchbase('key');
// 返回 value
```

### 设置60秒后就过期的缓存
```php
widget()->couchbase('key', 'value', 60);
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
-------

### 选项

| 名称       | 类型         | 默认值         | 说明                                                    |
|------------|--------------|----------------|---------------------------------------------------------|
| host       | array|string | 127.0.0.1:8091 | Couchbase所在的服务器名称,端口为可选,默认端口是`8091`   |
| user       | string       | 无             | 用于服务器验证的用户名                                  |
| password   | string       | 无             | 用于服务器验证的密码                                    |
| bucket     | string       | default        | 连接的桶的名称                                          |
| persistent | bool         | true           | 是否使用长连接                                          |

### 方法

#### couchbase($key, $value, $expire = 0)
设置缓存的值

##### 参数

| 名称      | 类型      | 默认值    | 说明                                  |
|-----------|-----------|-----------|---------------------------------------|
| $key      | string    | 无        | 缓存的键名                            |
| $value    | mixed     | 无        | 缓存的值,允许任意类型                 |
| $expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期   |

#### couchbase($key)
获取指定名称的缓存

#### couchbase->set($key, $value, $expire = 0)
设置缓存的值,同`couchbase($key, $value, $expire = 0)`

#### couchbase->get($key)
获取缓存的值,同`couchbase($key)`

#### couchbase->remove($key)
移除一项缓存

#### couchbase->exists($key)
检查缓存是否存在

#### couchbase->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### couchbase->replace($key, $value)
替换一项缓存,如果缓存不存在,返回false

#### couchbase->increment($key, $offset = 1)
增大一项缓存的值

#### couchbase->decrement($key, $offset = 1)
减小一项缓存的值

#### couchbase->getMulti($keys)
批量获取缓存的值

#### couchbase->setMulti($values)
批量设置缓存的值
