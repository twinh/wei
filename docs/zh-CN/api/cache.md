Cache
=====

缓存数据管理,可设定Memcached,Redis,APC,文件等作为缓存驱动

案例
----

### 设置和获取缓存

```php
// 设置缓存,返回true
widget()->cache('key', 'value');

// 获取缓存,返回'value'
widget()->cache('key');
```

### 设置60秒后就过期的缓存

```php
widget()->cache('key', 'value', 60);
```

### 配置缓存驱动为`Redis`

```php

// 配置缓存驱动为`redis`,同时配置redis的服务器地址
widget(array(
	'cache' => array(
		'driver' => 'redis'
	),
	'redis' => array(
		'host' => '127.0.0.1',
	)
));

// 获取缓存对象
$cache = widget()->cache;

// 输出缓存驱动, 输出为`redis`
echo $cache->getDriver();
```

### 缓存数据库查询,如缓存用户总数,每30秒更新一次

```php
$totalUsers = widget()->cache->get('totalUsers', 30, function($widget){
	return $widget->db->fetchColumn("SELECT COUNT(1) FROM user");
});
```

### 将缓存作为计数器,记录文章访问次数

```php
$cache = widget()->cache;

// 设置文章访问次数增加1,返回增加后的总次数
// 注意: 开发人员无需预先判断该键名的缓存是否存在,如果缓存不存在,将自动从0开始计算
$hits = $cache->inc('article-1', 1);

echo '该文章已被访问' . $hits . '次';
```

### 使用缓存前缀来避免缓存名称冲突

有些时候,我们会共享同一个memcched服务,共享同一个APC,这时可以通过设置缓存名称前缀,避免数据冲突

```php
// 设置APC缓存的键名前缀
widget(array(
    'memcached' => array(
        'keyPrefix' => 'project-'
    )
));

// 缓存键名将自动转换为'project-key'
widget()->memcached->set('key', 'value');
```

### 批量设置和获取缓存

**注意:** 目前只有`redis`和`couchbase`支持原生的批量设置,其他的缓存实际都是通过`foreach`语句逐个设置.

```php
$cache = widget()->cache;

// 批量设置缓存
$result = $cache->setMulti(array(
	'array' 	=> array(),
	'bool'		=> true,
	'float'		=> 1.2,
	'int'		=> 1,
	'null'		=> null,
	'object'	=> new \stdClass()
));

// 返回结果
$result = array (
  'array' => true,
  'bool' => true,
  'float' => true,
  'int' => true,
  'null' => true,
  'object' => true,
);

// 批量获取缓存
$result = $cache->getMulti(array(
    'array',
    'bool',
    'float',
    'int',
    'null',
    'object'
));

// 返回结果
$result = array (
  'array' => array (),
  'bool' => true,
  'float' => 1.2,
  'int' => 1,
  'null' => NULL,
  'object' => stdClass::__set_state(array()),
);
```

调用方式
--------

### 通用选项

名称      | 类型   | 默认值    | 说明
----------|--------|-----------|------
keyPrfix  | string | 无        | 缓存名称的前缀

### 选项

名称      | 类型   | 默认值    | 说明
----------|--------|-----------|------
driver    | string | apc       | 缓存的类型

#### 支持的缓存类型

* [apc](apc.md) - APC缓存 *(推荐)*
* [arrayCache](arrayCache.md) - PHP数组缓存
* [couchbase](couchbase.md) - Couchbase缓存
* [dbCache](dbCache.md) - 数据库缓存
* [fileCache](fileCache.md) - 文件缓存
* [memcache](memcache.md) - Memcachce缓存
* [memcached](memcached.md) - Memcached缓存 *(推荐)*
* [mongoCache](mongoCache.md) - MongoDB缓存
* [redis](redis.md) - Redis缓存 *(推荐)*
* [bicache](bicache.md) - 二级缓存

#### 特性对比

特性   | Apc | ArrayCache | DbCache | FileCache | Memecache | Memcached | MongoCache | Couchbase | Redis
-------|-----|------------|---------|-----------|-----------|-----------|------------|-----------|-------
速度   | 快  | 快         | 慢      | 慢        | 快        | 快        | 快         | 快        | 快
持久化 | ×  | -          |  √     | √        | ×        | ×        | √         | √        | √
分布式 | ×  | ×         |  √     | ×        | √        | √        | √         | √        | √
原子性 | √  | √         |  ×     | √        | √        | √        | √         | √        | √

* `√` 表示支持
* `×` 表示不支持
* `-`  表示不具备该特性

说明:

* 速度:判断依据是缓存数据存储的介质,如果数据存储在内存中则`快`,如果存储在磁盘则`慢`
* 持久化:判断依据是缓存是否因为相关服务重启而丢失数据
* 原子性:判断依据是`add`,`replace`,`inc`和`dec`方法是否为原子操作
* `Cache`和`Bicache`为视配置而定,不列入比较
* `ArrayCache`使用PHP数组存储数据,每次请求结束后销毁,因此不具备持久化特性

### 通用方法

在下面的方法中,`cache`表示缓存微件的名称,可以替换为其他任意缓存,如`redis`, `memcached`等

#### cache($key, $value, $expire = 0)

设置缓存的值

返回: `bool` 是否设置成功

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$value    | mixed     | 无        | 缓存的值,允许任意变量类型
$expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期

#### cache($key)

获取指定名称的缓存

返回: `mixed`

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名

#### cache->set($key, $value, $expire = 0)

设置缓存的值,同`cache($key, $value, $expire = 0)`

#### cache->get($key)

获取缓存的值,同`cache($key)`

#### cache->remove($key)

移除一项缓存

返回: `bool` 是否移除成功

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名

#### cache->exists($key)

检查缓存是否存在

返回: `bool`

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名

#### cache->add($key, $value)

增加一项缓存,如果缓存已存在,返回false

返回: `bool`

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$value    | mixed     | 无        | 缓存的值,允许任意变量类型

#### cache->replace($key, $value)

替换一项缓存,如果缓存不存在,返回false

返回: `bool`

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$value    | mixed     | 无        | 缓存的值,允许任意变量类型

#### cache->inc($key, $offset = 1)

增大一项缓存的值

返回: `int` 增大后缓存的值

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$offset   | int       | 1         | 增大的值

#### cache->dec($key, $offset = 1)

减小一项缓存的值

返回: `int` 减小后缓存的值

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$offset   | int       | 1         | 减小的值

#### cache->getMulti($keys)

批量获取缓存的值

返回: `array`  数组的键名是缓存的名称,值是缓存的值,如果某项缓存不存在,它的值为`false`

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$keys     | array     | 无        | 缓存的键名数组

#### cache->setMulti($values)

批量设置缓存的值

返回: `array`  数组的键名是缓存的名称,值操作结果,即`true`和`false`

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$values   | array     | 无        | 要缓存的数据
