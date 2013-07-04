Session
=======

管理用户会话信息

案例
----

### 设置和获取会话信息
```php
widget()->session('verfiyCode', 'WIDG');

if (widget()->sessio('verfiyCode') == widget()->post('verfiyCode')) {
    echo '验证码正确';
} else {
    echo '验证码错误';
}
```

调用方式
--------

### 选项

| 名称          | 类型      | 默认值    | 说明                                                    | 
|---------------|-----------|-----------|---------------------------------------------------------|
| namespace     | string    | widget    | 存储会话信息的命名空间,因为在PHP中,用户会话信息是共享的 |

### 方法

#### session($key)
获取会话信息的值

#### session($key, $value)
设置会话信息的值

#### session->get($key)
获取会话信息的值,同`session($key)`

#### session->set($key, $value)
设置会话信息的值,同`session($key, $value)`

#### session->clear()
删除当前命名空间的会话信息

#### session->destory()
删除所有的会话消息

#### session->setInis(array $inis)
设置会话相关的ini配置,配置名称会自定加上`session.`前缀  
详细配置可以查看http://php.net/manual/en/session.configuration.php
