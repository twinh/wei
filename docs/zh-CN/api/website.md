Website
=======

设置或获取网站的配置

案例
----

### 设置网站的标题和URL
```php
// 配置网站的标题为`Widget Documentation`
widget()->website('title', 'Widget Documentation');

// 输出网站标题
echo widget()->website('title');

// 配置网站的URL
widget()->website('url', 'http://www.example.com');

// 输出网站URL
echo widget()->website('url');
```

调用方式
--------

### 选项

Website微件接受任意选项作为网站配置,因为她只负责设置和获取配置,并不关心配置的名称和内容

### 方法

#### website($name)
获取网站配置

#### website($name, $value)
设置网站配置
