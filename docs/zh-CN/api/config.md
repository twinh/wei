Config
======

设置或获取自定义配置

案例
----

### 设置和获取配置

```php
// 设置配置项'title'的值为'Widget Documentation'
widget()->config('title', 'Widget Documentation');

// 输出配置项'title'的值
echo widget()->config->get('title');

// 设置配置项'url'的值
widget()->config->set('url', 'http://www.example.com');

// 输出配置项'url'的值
echo widget()->config->get('url');
```

调用方式
--------

### 选项

*无*

### 方法

#### config($name, $default)
获取配置

#### config($name, $value)
设置配置

#### config->get($name, $default = null)
获取配置

#### config->set($name, $value)
设置配置
