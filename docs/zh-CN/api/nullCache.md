NullCache
=========

空缓存，不存储数据。

读数据时，总是返回返回失败，写数据时，总是返回成功。

主要用于测试，或者想要去掉缓存的场景（如 CLI）。

案例
----

### 设置和获取缓存

```php
use Wei\NullCache;

// 设置缓存，总是返回 true
NullCache::set('key', 'value');

// 获取缓存，总是返回 null
NullCache::get('key');
```

更多案例请查看 [Cache](cache.md) 类

调用方式
-------

### 选项

*无*

### 继承的方法

通用方法请查看 [Cache](cache.md#通用方法) 类

### 方法

*无*
