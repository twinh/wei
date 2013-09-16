Redirect
========

跳转到指定地址

案例
----

### 等待3秒后跳转d到谷歌首页
```php
widget()->redirect('http://www.google.com', 302, array(
    'wait' => 3
));
```

### 直接跳转到谷歌首页
```php
widget()->redirect('http://www.google.com');
```

调用方式
--------

### 选项

名称          | 类型      | 默认值    | 说明
--------------|-----------|-----------|------
wait          | int       | 0         | 等待跳转的秒数,如果wait为0,使用的是HTTP header跳转,如果不为0,因为HTTP header不支持延迟跳转,所以使用的是HTML [Meta refresh](http://en.wikipedia.org/wiki/Meta_refresh)跳转
statusCode    | int       | 302       | 跳转的HTTP状态码
cache         | bool      | false     | 默认为false,表示向URL增加时间截参数,不让浏览器缓存URL跳转地址
view          | string    | 无        | 自定义跳转视图的文件,可以设置该选项以展示更加友好的跳转视图

### 方法

#### redirect($url, $status = 302, $options = array())
跳转到指定地址

#### redirect->setWait($wait)
设置默认的等待时间

#### redirect->setView($view)
设置默认的跳转视图