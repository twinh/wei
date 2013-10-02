Error
=====

错误处理器,可自定义异常,404和fatal错误的处理

案例
----

### 自定义404错误页面
```php
widget()->error->notFound(function($exception){
    // 输出错误提醒,这里可以改成加载错误视图,记录错误日志等
    echo 'Page not found';

    // 返回true表示错误已处理,不再输出默认的错误也页面
    return true;
});

// 抛出404错误
throw new \Exception('Page not found', 404);
```

### 选项

名称                | 类型   | 默认值                                                                           | 说明
--------------------|--------|----------------------------------------------------------------------------------|------
message             | string | Error                                                                            | 错误标题
detail              | string | Unfortunately, an error occurred. Please try again later.                        | 未开启调试模式时的错误信息
notFoundDetail      | string | Sorry, the page you requested was not found. Please check the URL and try again. | 未开启调试模式时的404错误信息
ignorePrevHandler   | bool   | false                                                                            | 是否忽略已有的异常处理器

### 方法

#### error($fn)
设置自定义的错误处理器

处理器的参数如下表.

名称        | 类型          | 说明
------------|---------------|------
$exception  | Exception     | 异常对象
$widget     | Widget\Widget | 对象管理器

### error->notFound($fn)
设置404的错误处理器

### error->fatal($fn)
设置Fatal错误的处理器
