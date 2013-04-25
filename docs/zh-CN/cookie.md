Cookie
======

读取,写入和删除Cookie

案例
----

### 设置和获取Cookie的值
```php
// 返回true
$widget->cookie('key', 'value');

// 返回value
$widget->cookie('key');
```

### 设置7天过期的Cookie
```php
$widget->cookie('key', 'value', array('expire' => 7));
```

### 删除名称为'logined'的cookie
```php
$widget->cookie->remove('logined');
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

#### cookie->set($key)
写入cookie,同`cookie($key, $value, $options = array())`

#### cookie->remove($key)
删除cookie

#### cookie->send()
发送cookie到浏览器
