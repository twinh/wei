Cache
=====

缓存数据服务,可设定Memcached,Redis,APC,文件等作为缓存驱动

案例
----

### 设置和获取缓存

```php
// 设置缓存,成功返回true,失败返回false
wei()->cache('key', 'value');

// 获取缓存,返回'value'
wei()->cache('key');
```

### 设置60秒后就过期的缓存

```php
wei()->cache('key', 'value', 60);
```

### 配置缓存驱动为`Redis`

```php
wei(array(
    // 配置缓存驱动为`redis`
	'cache' => array(
		'driver' => 'redis'
	),
    // 配置redis的服务器地址
	'redis' => array(
		'host' => '127.0.0.1',
	)
));

// 获取缓存对象
$cache = wei()->cache;

// 输出缓存驱动, 输出为`redis`
echo $cache->getDriver();
```

### 缓存数据库查询等耗时较长的操作

如缓存用户总数,每30秒缓存过期,并重新拉取

```php
$totalUsers = wei()->cache->remember('totalUsers', 30, function($wei){
	return $wei->db->fetchColumn("SELECT COUNT(1) FROM user");
});
```

将用户编号和最后更新时间作为缓存键名,省略过期时间参数

```php
$user = array(
    'id' => 1,
    'updateTime' => '2013-12-01 12:00:00'
);

wei()->cache->remember($user['id'] . $user['updateTime'], function($wei){
    // 渲染复杂的视图,拉取远程接口等耗时较长的操作
    return $wei->view->render('userInfo.php');
});
```

### 将缓存作为计数器,记录文章访问次数

设置文章访问次数增加1,返回增加后的总次数

```php
$hits = wei()->cache->incr('article-1', 1);

echo '该文章已被访问' . $hits . '次';
```

> #### 注意
>
> 您无需预先判断该键名的缓存是否存在,如果缓存不存在,将自动从0开始计算

### 使用键名前缀来避免缓存名称冲突

有些时候,我们会在多个应用中,共享同一个memcched服务,共享同一个APC,这时可以通过设置键名前缀,来避免数据冲突

```php
// 设置memcached缓存的键名前缀
wei(array(
    'memcached' => array(
        'prefix' => 'project-'
    )
));

// 缓存键名将自动转换为'project-key'
wei()->memcached->set('key', 'value');
```

### 批量设置和获取缓存

**注意:** 目前只有`redis`支持原生的批量设置,其他的缓存实际都是通过`foreach`语句逐个设置.

```php
$cache = wei()->cache;

// 批量设置缓存
$result = $cache->setMultiple(array(
    'array' 	=> array(),
    'bool'		=> true,
    'float'		=> 1.2,
    'int'		=> 1,
    'null'		=> null,
    'object'	=> new \stdClass()
));

// 返回结果
$result = true;

// 批量获取缓存
$result = $cache->getMultiple(array(
    'array',
    'bool',
    'float',
    'int',
    'null',
    'object'
));

// 返回结果
$result = array (
    'array' => array(),
    'bool' => true,
    'float' => 1.2,
    'int' => 1,
    'null' => NULL,
    'object' => stdClass::__set_state(array()),
);
```

### 根据文件路径和最后修改时间,生成缓存内容

常用场景

1. 缓存JSON,YAML,INI等非PHP原生支持文件的解析结果
2.  在自定义视图引擎中,缓存模板文件的编译结果

```php
wei()->cache->getFileContent($file, function($file) {
    return json_decode(file_get_contents($file), true);
    //return \Symfony\Component\Yaml\Yaml::parse($file);
});
```

调用方式
--------

### 通用选项

名称      | 类型   | 默认值    | 说明
----------|--------|-----------|------
prefix    | string | 无        | 缓存名称的前缀

### 选项

名称      | 类型   | 默认值  | 说明
----------|--------|------|------
driver    | string | apcu | 缓存的类型

#### 支持的缓存类型

* [apcu](apcu.md) - APC缓存 *(推荐)*
* [arrayCache](arrayCache.md) - PHP数组缓存
* [dbCache](dbCache.md) - 数据库缓存
* [fileCache](fileCache.md) - 文件缓存
* [memcache](memcache.md) - Memcachce缓存
* [memcached](memcached.md) - Memcached缓存 *(推荐)*
* [mongoCache](mongoCache.md) - MongoDB缓存
* [redis](redis.md) - Redis缓存 *(推荐)*
* [bicache](bicache.md) - 二级缓存
* [nullCache](nullCache.md) - 空缓存

#### 特性对比

特性   | Apcu | ArrayCache | DbCache | FileCache | Memecache | Memcached | MongoCache  | Redis
-------|------|------------|---------|-----------|-----------|-----------|------------|-------
速度   | 快    | 快         | 慢      | 慢        | 快        | 快        | 快         | 快
持久化 | ×    | -          |  √     | √        | ×        | ×        | √         | √
分布式 | ×    | ×         |  √     | ×        | √        | √        | √         | √
原子性 | √    | √         |  ×     | √        | √        | √        | ×         | √

* `√` 表示支持
* `×` 表示不支持
* `-`  表示不具备该特性

说明:

* 速度:判断依据是缓存数据存储的介质,如果数据存储在内存中则`快`,如果存储在磁盘则`慢`
* 持久化:判断依据是缓存是否因为相关服务重启而丢失数据
* 原子性:判断依据是`add`,`replace`,`incr`和`decr`方法是否为原子操作
* `Cache`和`Bicache`为视配置而定,不列入比较
* `ArrayCache`使用PHP数组存储数据,每次请求结束后销毁,因此不具备持久化特性

### 通用方法

在下面的方法中,`cache`表示缓存类的名称,可以替换为其他任意缓存,如`redis`, `memcached`等

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

#### cache->delete($key)

移除一项缓存

返回: `bool` 缓存存在时返回`true`,不存在返回`false`

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名

#### cache->has($key)

检查缓存是否存在

返回: `bool`

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名

#### cache->add($key, $value, $expire = 0)

增加一项缓存,如果缓存已存在,返回false

返回: `bool`

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$value    | mixed     | 无        | 缓存的值,允许任意变量类型
$expire   | int       | 0         | 缓存的有效期,默认为0秒,表示永不过期

#### cache->replace($key, $value)

替换一项缓存,如果缓存不存在,返回false

返回: `bool`

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$value    | mixed     | 无        | 缓存的值,允许任意变量类型

#### cache->incr($key, $offset = 1)

增大一项缓存的值

返回: `int` 增大后缓存的值

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$offset   | int       | 1         | 增大的值

#### cache->decr($key, $offset = 1)

减小一项缓存的值

返回: `int` 减小后缓存的值

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$key      | string    | 无        | 缓存的键名
$offset   | int       | 1         | 减小的值

#### cache->getMultiple($keys)

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

#### cache->clear()

清除所有的缓存

返回: bool

> #### 注意
>
> 该方法会把所有的缓存内容都清空,不仅仅是以$prefix开头的缓存.因为大部分缓存未提供按前缀清空缓存的功能.

#### cache->getFileContent($file, $fn)

根据文件路径和最后修改时间,生成缓存内容

#### cache->getPrefix()

获取键名前缀

#### cache->setPrefix($prefix)

设置键名前缀
