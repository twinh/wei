Session
=======

管理用户会话信息

案例
----

### 使用Session存储验证码,并校验请求的验证码是否正确

验证码页面

```php
widget()->session('verfiyCode', 'WIDG');
``

校验页面

```php
if (widget()->session('verfiyCode') == widget()->request('verfiyCode')) {
    echo '验证码正确';
} else {
    echo '验证码错误';
}
```

调用方式
--------

### 选项

名称      | 类型         | 默认值    | 说明
----------|--------------|-----------|------
namespace | string,false | false     | 存储会话信息的命名空间,默认不启用
inis      | array        | 见下表    | PHP会话配置选项

#### inis选项的值

所有的配置选项和默认值请查阅 http://php.net/manual/en/session.configuration.php

名称 			| 值
----------------|----
cache_limiter   | private_no_expire
cookie_lifetime | 86400
cache_expire 	| 86400
gc_maxlifetime	| 86400

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
