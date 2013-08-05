Redis
=====

设置或获取一项缓存,缓存数据存储于Redis中

案例
----

### 设置和获取缓存
```php
// 设置缓存
widget()->redis('key', 'value');
// 返回 true

// 获取缓存
widget()->redis('key');
// 返回 value
```

### 设置60秒后就过期的缓存
```php
widget()->redis('key', 'value', 60);
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
--------

### 选项

名称       | 类型         | 默认值         | 说明
-----------|--------------|----------------|------
host       | string       | 127.0.0.1      | Redis所在的服务器名称
prot       | int          | 6379           | Redis所在的服务器端口
timeout    | float        | 0.0            | 连接服务器的超时秒数
persistent | bool         | true           | 是否使用长连接
object     | \Redis       | 无             | 原始的Redis对象

### 方法

#### redis($key, $value, $expire = 0)
设置缓存的值

返回: `bool` 是否设置成功

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$value    | mixed     | 无        | 缓存的值,允许任意类型
$expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期

#### redis($key)
获取指定名称的缓存

#### redis->set($key, $value, $expire = 0)
设置缓存的值,同`redis($key, $value, $expire = 0)`

#### redis->get($key)
获取缓存的值,同`redis($key)`

#### redis->remove($key)
移除一项缓存

#### redis->exists($key)
检查缓存是否存在

#### redis->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### redis->replace($key, $value)
替换一项缓存,如果缓存不存在,返回false

#### redis->inc($key, $offset = 1)
增大一项缓存的值

#### redis->dec($key, $offset = 1)
减小一项缓存的值

#### redis->getMulti($keys)
批量获取缓存的值

#### redis->setMulti($values)
批量设置缓存的值
