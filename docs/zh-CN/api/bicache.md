Bicache
=======

设置或获取缓存,数据存储在二级缓存中

二级缓存使用主从同步机制,由一个主缓存(master)和一个从缓存(slave)组成,二级缓存的原理非常简单,有以下两点.

1. 每次读取数据时,如果主缓存读取不到数据,将从从缓存读取数据
2. 每次写入数据时,如果检查上次更新到从缓存的间隔秒数大于配置秒数,则同步数据到从缓存中

案例
----

### 设置和获取缓存
```php
// 设置缓存
widget()->bicache('key', 'value');
// 返回 true

// 获取缓存
widget()->bicache('key');
// 返回 value
```

### 设置60秒后就过期的缓存
```php
widget()->bicache('key', 'value', 60);
```

调用方式
-------

### 选项

| 名称      | 类型   | 默认值    | 说明                                                                                  |
|-----------|--------|-----------|---------------------------------------------------------------------------------------|
| time      | int    | 5         | 当主缓存写数据时,会检查上次更新到slave缓存的间隔秒数,如果超过该秒数,就同步到从缓存中  |
| deps      | array  |           | 主从缓存类型的配置                                                                    |
|  - master | string | apc       | 主缓存的类型,推荐使用内存类缓存,如apc,redis,memcache,memcached或couchbase             |
|  - slave  | string | fileCache | 从缓存的类型,推荐使用文件类缓存,如fileCache或dbCache                                  |

### 方法

#### bicache($key, $value, $expire = 0)
设置缓存的值

##### 参数

| 名称      | 类型      | 默认值    | 说明                                  |
|-----------|-----------|-----------|---------------------------------------|
| $key      | string    | 无        | 缓存的键名                            |
| $value    | mixed     | 无        | 缓存的值,允许任意类型                 |
| $expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期   |

#### bicache($key)
获取指定名称的缓存

#### bicache->set($key, $value, $expire = 0)
设置缓存的值,同`bicache($key, $value, $expire = 0)`

#### bicache->get($key)
获取缓存的值,同`bicache($key)`

#### bicache->remove($key)
移除一项缓存

#### bicache->exists($key)
检查缓存是否存在

#### bicache->add($key, $value)
增加一项缓存,如果缓存已存在,返回false

#### bicache->replace($key, $value)
替换一项缓存,如果缓存不存在,返回false

#### bicache->increment($key, $offset = 1)
增大一项缓存的值

#### bicache->decrement($key, $offset = 1)
减小一项缓存的值

#### bicache->getMulti($keys)
批量获取缓存的值

#### bicache->setMulti($values)
批量设置缓存的值
