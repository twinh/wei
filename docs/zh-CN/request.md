Request
=======

管理HTTP请求数据/获取一项HTTP请求参数($_REQUEST)的值

案例
----

### 获取HTTP请求参数的值
```php
// 假设 $_REQUEST['id'] = 5;

// 返回5
$id = $widget->request('id');
```

### 获取当前请求的URL地址
```php
$url = $widget->request->getUrl();
```