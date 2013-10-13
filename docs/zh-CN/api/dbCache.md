DbCache
=======

设置或获取一项缓存,缓存数据存储于数据库中

dbCache对象依赖于[db](db.md)对象

案例
----

### 设置和获取缓存

```php
// 设置缓存,返回true
wei()->dbCache('key', 'value');

// 获取缓存,返回'value'
wei()->dbCache('key');
```

### 设置60秒后就过期的缓存

```php
wei()->dbCache('key', 'value', 60);
```

更多案例请查看[Cache](cache.md)对象

调用方式
--------

### 选项

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
table     | string    | cache     | 缓存数据表名称

### 继承的方法

通用方法请查看[Cache](cache.md#通用方法)对象

### 方法

*无*
