ArrayCache
==========

数组缓存,将数据存储在PHP数组中,每次 **请求结束** 数据就会被 **清空**.

使用数组缓存主要有两个场景:

1. 在开发环境使用,确保每次页面请求都会获得最新生成的数据
2. 对计算结果进行临时存储,以便在不同地方获取数据,减少重复计算消耗的时间

案例
----

### 设置和获取缓存

```php
// 设置缓存
widget()->arrayCache('key', 'value');
// 返回 true

// 获取缓存
widget()->arrayCache('key');
// 返回 value
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
--------

### 选项

*无*

### 方法

#### arrayCache ($key, $value)
设置缓存的值
```php
widget()->arrayCache('key', 'value');
```

#### arrayCache($key)
获取指定名称的缓存
```php
widget()->arrayCache('name');
```

#### arrayCache->set($key, $value)
设置缓存的值

#### arrayCache->get($key)
获取缓存的值

#### arrayCache->remove($key)
移除一项缓存

#### arrayCache->exists($key)
检查缓存是否存在

#### arrayCache->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### arrayCache->replace($key, $value)
替换一项缓存,如果缓存 **不** 存在,返回false

#### arrayCache->increment($key, $offset = 1)
增大一项缓存的值

#### arrayCache->decrement($key, $offset = 1)
减小一项缓存的值

#### arrayCache->getMulti($keys)
批量获取缓存的值

#### arrayCache->setMulti($values)
批量设置缓存的值
