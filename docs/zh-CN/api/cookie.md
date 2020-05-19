Cookie
======

写入,读取和删除cookie($_COOKIE)

案例
----

### 写入和读取cookie的值
```php
// 写入cookie,返回true
wei()->cookie('key', 'value');

// 读取cookie返回value
wei()->cookie('key');
```

### 写入7天后过期的cookie
```php
wei()->cookie('key', 'value', array('expires' => 7 * 86400));
```

### 删除名称为'logined'的cookie
```php
wei()->cookie->remove('logined');
```

调用方式
-------

### 选项

*无*

### 方法

#### cookie($key)
读取cookie

#### cookie($key, $value, $options = array())
写入cookie

#### cookie->get($key)
读取cookie,同`cookie($key)`

#### cookie->set($key, $value, $options = array())
写入cookie,同`cookie($key, $value, $options = array())`

#### cookie->remove($key)
删除cookie
