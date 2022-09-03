Lock
====

锁服务,用于对请求,资源进行加锁,防止重复操作

案例
----

### 常用场景

* 限制用户只能领取一个礼包
* 对用户增加或减少积分
* 限制用户只能购买一件商品

### 对耗时较长的请求进行加锁,防止重复操作

在一些耗时长的请求(如提交表单,上传文件,实时发送邮件等),可以通过加锁防止重复提交,导致数据重复.

```php
if (!wei()->lock('longTimeJob')) {
    echo '您的请求还在执行中,请稍后再试';
    return;
}

// 执行长时间的任务
sleep(5);

echo '操作成功';
```

### 对包含多个重要操作的请求进行加锁,实现请求原子化

```php
$userId = '123456';
if (!wei()->lock('lottery-' . $userId)) {
    echo '您的请求还在执行中,请稍后再试';
    return;
}

// 重要操作1: 扣除用户抽奖次数
// $lotteryCounter->decr($userId)

// 重要操作2: 执行抽奖操作
// $lottery->draw();
```

> #### 注意
>
> 在请求结束后,所有的锁都会被自动释放

### 配置锁服务

锁服务依赖于缓存服务,您可以通过`providers`选项配置.

推荐配置的缓存服务:`apcu`(单台服务器),`memcached`,`redis`等.

```php
wei(array(
    'lock' => array(
        'providers' => array(
            'cache' => 'memcached'
        )
    )
));
```

调用方式
--------

### 选项

*无*

### 方法

#### lock($key)
对指定的键名加锁

返回 `bool` 成功时返回`true`,失败返回`false`

#### lock->release($key)
对指定的键名解锁

返回 `bool` 键名存在时返回`true`,不存在时返回`false`

#### lock->releaseAll()
释放当前请求所有的锁
