Redis
=====

设置或获取一项缓存,缓存数据存储于Redis中

案例
----

### 设置和获取缓存
```php
// 设置缓存,返回true
widget()->redis('key', 'value');

// 获取缓存,返回'value'
widget()->redis('key');
```

### 设置60秒后就过期的缓存
```php
widget()->redis('key', 'value', 60);
```

更多案例请查看"[缓存](../book/cache.md)"章节

调用方式
--------

### 选项

名称       | 类型         | 默认值         | 说明
-----------|--------------|----------------|------
host       | string       | 127.0.0.1      | Redis所在的服务器名称
prot       | int          | 6379           | Redis所在的服务器端口
timeout    | float        | 0.0            | 连接服务器的超时秒数
persistent | bool         | true           | 是否使用长连接
auth 	   | string 	  | 无 			   | Redis服务器的验证密码
object     | \Redis       | 无             | 原始的Redis对象
options    | array 		  | -              | \Redis::setOption()方法的参数

### 继承的方法

通用方法请查看[cache](cache.md#通用方法)微件文档

### 方法

#### redis->getObject()
获取原生Redis对象

#### redis->setObject($redis)
设置原生Redis对象