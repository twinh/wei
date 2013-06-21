FileCache
=========

设置或获取文件缓存

案例
----

### 设置和获取文件缓存
```php
// 设置缓存
widget()->fileCache('key', 'value');
// true

// 获取缓存
widget()->fileCache('key');
// value
```

调用方式
--------

### 选项

*无*

### 方法

#### fileCache($key, $value, $expire = 0)
设置缓存的值

##### 参数

| 名称      | 类型      | 默认值    | 说明                                  |
|-----------|-----------|-----------|---------------------------------------|
| $key      | string    | 无        | 缓存的键名                            |
| $value    | mixed     | 无        | 缓存的值,允许任意类型                 |
| $expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期   |

#### fileCache($key)
获取指定名称的缓存

#### fileCache->set($key, $value, $expire = 0)
设置缓存的值,同`fileCache($key, $value, $expire = 0)`

#### fileCache->get($key)
获取缓存的值,同`fileCache($key)`

#### fileCache->remove($key)
移除一项缓存

#### fileCache->exists($key)
检查缓存是否存在

#### fileCache->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### fileCache->replace($key, $value)
替换一项缓存,如果缓存 **不** 存在,返回false

#### fileCache->increment($key, $offset = 1)
增大一项缓存的值

#### fileCache->decrement($key, $offset = 1)
减小一项缓存的值

#### fileCache->getMulti($keys)
批量获取缓存的值

#### fileCache->setMulti($values)
批量设置缓存的值
