Memcached
=========

设置或获取一项缓存,缓存数据存储于memcached中

案例
----

### 设置和获取缓存

```php
// 设置缓存,返回true
widget()->memcached('key', 'value');

// 获取缓存,返回'value'
widget()->memcached('key');
```

### 设置60秒后就过期的缓存

```php
widget()->memcached('key', 'value', 60);
```

更多案例请查看"[Cache](cache.md)"微件

调用方式
-------

### 选项

名称       | 类型         | 默认值                 | 说明
-----------|--------------|------------------------|------
servers    | array        | 见下表                 | 服务器配置数组
object     | \Memcached   | 无                     | 原始的memcached对象

#### 选项`servers`

名称       | 类型         | 默认值                 | 说明
-----------|--------------|------------------------|------
host       | string       | 127.0.0.1              | memcached服务器地址
port       | int          | 11211                  | memcached服务器端口

### 继承的方法

通用方法请查看[cache](cache.md#通用方法)微件文档

### 方法

#### memcached->getObject()
获取原生Memcached对象

#### memcached->setObject($memcached)
设置原生Memcached对象