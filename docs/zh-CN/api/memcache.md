Memcache
========

设置或获取一项缓存,缓存数据存储于Memcache中

案例
----

### 设置和获取缓存

```php
// 设置缓存,返回true
widget()->memcache('key', 'value');

// 获取缓存,返回'value'
widget()->memcache('key');
```

### 设置60秒后就过期的缓存

```php
widget()->memcache('key', 'value', 60);
```

更多案例请查看[Cache](cache.md)对象

调用方式
-------

### 选项

名称       | 类型         | 默认值                 | 说明
-----------|--------------|------------------------|------
servers    | array        | 见下表                 | 服务器配置数组
flag       | int          | `MEMCACHE_COMPRESSED`  | 用于服务器验证的用户名
object     | \Memcache    | 无                     | 原始的Memcache对象

#### 选项`servers`

名称       | 类型         | 默认值                 | 说明
-----------|--------------|------------------------|------
host       | string       | 127.0.0.1              | Memcache服务器地址
port       | int          | 11211                  | Memcache服务器端口
persistent | bool         | true                   | 是否使用长连接

### 继承的方法

通用方法请查看[Cache](cache.md#通用方法)对象

### 方法

#### memcache->getObject()
获取原生Memcache对象

#### memcache->setObject($memcache)
设置原生Memcache对象