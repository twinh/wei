Counter
=======

计数器服务,可用于记录用户操作次数,判断用户是否执行了某项操作等

案例
----

### 配置计数器服务

计数器服务依赖于缓存服务,您可以通过`providers`选项配置.

推荐配置的缓存服务:`redis`,`dbCache`等.

```php
wei(array(
    'lock' => array(
        'providers' => array(
            'cache' => 'redis'
        )
    )
));
```

> #### 注意
>
> 应根据计数数据的重要性,选择持久性缓存或非持久性缓存.

调用方式
--------

### 选项

*无*

### 方法

#### counter->incr($key, $offset = 1)
增加一个计数器的值

#### counter->decr($key, $offset = 1)
减少一个计数器的值

#### counter->get($key)
获取一个计数器的值

#### counter->set($key, $value)
设置一个计数器的值

#### counter->exits($key)
检查一个计数器是否存在

#### counter->remove($key)
移除一个计数器