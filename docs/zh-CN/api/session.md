Session
=======

管理用户会话信息

案例
----

### 使用Session存储验证码,并校验请求的验证码是否正确

验证码页面

```php
wei()->session('verfiyCode', 'WIDG');
```

校验页面

```php
if (wei()->session('verfiyCode') == wei()->request('verfiyCode')) {
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
inis      | array        | array()   | PHP会话配置选项, http://php.net/manual/en/session.configuration.php

### 方法

#### session($key)
获取会话信息的值

#### session($key, $value)
设置会话信息的值

#### session->get($key)
获取会话信息的值,同`session($key)`

#### session->set($key, $value)
设置会话信息的值,同`session($key, $value)`

#### session->remove($key)
删除指定会话名称

#### session->exists($key)
检查指定会话名称是否存在

#### session->clear()
删除当前命名空间的会话信息

#### session->destory()
删除所有的会话信息

#### session->toArray();
获取当前命名空间下所有会话信息
