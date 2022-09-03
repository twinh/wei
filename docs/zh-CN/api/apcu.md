Apcu
====

通过面向对象的方式设置或获取PHP APCu缓存,并提供更多友好方便的功能方法.

案例
----

### 设置和获取缓存

```php
// 设置缓存,返回true
wei()->apcu('key', 'value');

// 获取缓存,返回'value'
wei()->apcu('key');
```

### 设置60秒后就过期的缓存

```php
wei()->apcu('key', 'value', 60);
```

更多案例请查看[Cache](cache.md)类

调用方式
-------

### 选项

*无*

### 继承的方法

通用方法请查看[Cache](cache.md#通用方法)类

### 方法

*无*
