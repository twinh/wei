[redis()](http://twinh.github.com/widget/api/redis)
===================================================

设置或获取一项Redis缓存

##### 目录
* redis( $key, $value [ $expire ] ) 
* redis( $key )
* redis->remove( $key )

### 设置一项Redis缓存
```php
bool redis( $key, $value [ $expire ] ) 
```

##### 参数
* **$key** `string` 缓存的键名
* **$value** `mixed` 缓存的值,允许任意类型
* **$expire** `int` 缓存的有效期,默认为0秒,表示永不过期


该调用方式相等于`$widget->redis->set($key, $value, $expire)`


##### 代码范例
设置键名为"key",值为"value"的缓存
```php
<?php

$widget->redis('key', 'value');

echo $widget->redis('key');
```
##### 运行结果
```php
'value'
```
- - - -

### 获取一项Redis缓存
```php
bool redis( $key )
```

##### 参数
* **$key** `string` 缓存的键名


该调用方式相等于`$widget->redis->get($key)`


##### 代码范例
获取键名为"key"的缓存,并打印出来
```php
<?php

$widget->redis('key', 'value');

echo $widget->redis('key');
```
##### 运行结果
```php
'value'
```
- - - -

### 删除指定键名的缓存
```php
bool redis->remove( $key )
```

##### 参数
* **$key** `string` 缓存的键名

