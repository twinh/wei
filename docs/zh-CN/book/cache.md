缓存
====

Widget提供了多个PHP流行的后端缓存.这里有apc,redis,memcached等主流缓存,也有简单的文件缓存fileCache,
可用于简单数据分析的数据库缓存dbCache等等.所有缓存拥有一致的接口,可以随意替换.

## 案例

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
$hits = $cache->increment('article-1', 1);

echo '该文章已被访问' . $hits . '次';
```

### 批量设置和获取缓存

**注意:** 目前只有`couchbase`和`redis`支持原生的批量设置,其他的缓存都是逐个设置.

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

## 支持的缓存类型及比较

### 支持的缓存类型

* [apc](../api/apc.md) - APC缓存
* [arrayCache](../api/arrayCache.md) - PHP数组缓存
* [couchbase](../api/couchbase.md) - Couchbase缓存
* [dbCache](../api/dbCache.md) - 数据库缓存
* [fileCache](../api/fileCache.md) - 文件缓存
* [memcache](../api/memcache.md) - Memcachce缓存
* [memcached](../api/memcached.md) - Memcached缓存
* [redis](../api/redis.md) - Redis缓存
* [cache](../api/cache.md) - 通用缓存
* [bicache](../api/bicache.md) - 二级缓存

### 特性对比

特性   | ArrayCache | Apc | DbCache | FileCache | Memecache | Memcached | MongoCache | Couchbase | Redis
-------|------------|-----|---------|-----------|-----------|-----------|------------|-----------|-------
速度   | 快         | 快  | 慢      | 慢        | 快        | 快        | 快         | 快        | 快
持久化 | -          | ×  | √      | √        | ×        | ×        | √         | √        | √
分布式 | ×         | ×  | √      | ×        | √        | √        | √         | √        | √
原子性 | √         | √  | ×      | √        | √        | √        | √         | √        | √

* `√` 表示支持
* `×` 表示不支持
* `-`  表示不具备该特性

* 速度:判断依据是缓存数据存储的介质,如果数据存储在内存中则`快`,如果存储在磁盘则`慢`
* 持久化:判断依据是缓存是否因为相关服务重启而丢失数据
* 原子性:判断依据是`add`,`replace`,`increment`和`decrement`方法是否为原子操作
* `Cache`和`Bicache`为视配置而定,不列入比较
* `ArrayCache`使用PHP数组存储数据,每次请求结束后销毁,因此不具备持久化特性

## 通用方法

在下面的方法中,`cache`表示缓存微件的名称,可以替换为其他任意缓存,如`redis`, `memcached`

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

#### cache->increment($key, $offset = 1)

增大一项缓存的值

返回: `int` 增大后缓存的值

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$offset   | int       | 1         | 增大的值

#### cache->decrement($key, $offset = 1)

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