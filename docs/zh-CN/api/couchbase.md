Couchbase
=========

设置或获取一项缓存,缓存数据存储于Couchbase中

案例
----

### 设置和获取缓存

```php
// 设置缓存,返回true
widget()->couchbase('key', 'value');

// 获取缓存,返回'value'
widget()->couchbase('key');
```

### 设置60秒后就过期的缓存

```php
widget()->couchbase('key', 'value', 60);
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
-------

### 选项

名称       | 类型         | 默认值         | 说明
-----------|--------------|----------------|------
host       | array|string | 127.0.0.1:8091 | Couchbase所在的服务器名称,端口为可选,默认端口是`8091`
user       | string       | 无             | 用于服务器验证的用户名
password   | string       | 无             | 用于服务器验证的密码
bucket     | string       | default        | 连接的桶的名称
persistent | bool         | true           | 是否使用长连接

### 继承的方法

通用方法请查看[cache](cache.md#通用方法)微件文档

### 方法

#### couchbase->getObject()
获取原生Couchbase对象

#### couchbase->setObject($couchbase)
设置原生Couchbase对象