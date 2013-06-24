Cookie
======

写入,读取和删除cookie($_COOKIE)

案例
----

### 写入和读取cookie的值
```php
// 写入cookie,返回true
widget()->cookie('key', 'value');

// 读取cookie返回value
widget()->cookie('key');
```

### 写入7天后过期的cookie
```php
widget()->cookie('key', 'value', array('expires' => 7 * 86400));
```

### 删除名称为'logined'的cookie
```php
widget()->cookie->remove('logined');
```

调用方式
-------

### 选项

| 名称      | 类型      | 默认值    | 说明                                                                      |
|-----------|-----------|-----------|---------------------------------------------------------------------------|
| expires   | int       | 86400     | 当用户使用谷歌浏览器时为true                                              |
| path      | string    | /         | cookie活动的路径                                                          |
| domain    | string    | null      | 保存该cookie的域名                                                        |
| secure    | bool      | false     | 是否只通过HTTPS安全连接来发送,只有在HTTPS连接下才有效                     |
| httpOnly  | bool      | false     | 是否只通过HTTP协议发送cookie,如果是,客户端javascript将无法读取到该cookie  |
| raw       | bool      | false     | 是否发送为不经过URL解码的cookie                                           |

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