MongoCache
==========

设置或获取一项缓存,缓存数据存储于[MangoDB](http://docs.mongodb.org/ecosystem/drivers/php/)中

案例
----

### 设置和获取缓存

```php
// 设置缓存,返回true
widget()->mongoCache('key', 'value');

// 获取缓存,返回'value'
widget()->mongoCache('key');
```

### 设置60秒后就过期的缓存

```php
widget()->mongoCache('key', 'value', 60);
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
-------

### 选项

名称       | 类型         | 默认值         | 说明
-----------|--------------|----------------|------
host       | string       | localhost      | MangoDB所在的服务器名称
port       | int          | 27017          | MangoDB所在的服务器的端口
db         | string       | cache          | 存储缓存数据的数据库的名称
collection | string       | cache          | 存储缓存数据的集合的名称

### 继承的方法

通用方法请查看[cache](cache.md#通用方法)微件文档

### 方法

*无*