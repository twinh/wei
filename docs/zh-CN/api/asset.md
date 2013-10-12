Asset
=====

生成带版本号的资源(CSS, JS, 图片等素材文件)URL地址,解决浏览器缓存资源文件的问题

案例
----

### 生成带版本号的资源URL地址,解决浏览器缓存资源文件的问题

```php
// 在配置文件中设置资源版本号
wei(array(
    'asset' => array(
        'version' => '1'
    )
));

// 在视图中使用asset服务生成URL地址
echo wei()->asset('style.css');

// 输出的URL地址类似
'/style.css?v=1'
```

### 自定义资源的基础URL地址

如果生成的资源地址不符合您的项目路径,你可以`baseUrl`更改资源路径

```php
wei(array(
    'asset' => array(
        'baseUrl' => 'project-dir/'
    )
));

echo wei()->asset('backgroud.jpg');

// 输出
'project-dir/backgroud.jpg?v=1';
```

### 使用多个资源服务

如果您的系统比较庞大,分为多个模块,通过简单配置即可为每个模块设置不同的资源服务对象.

```php
wei(array(
    // 定义
    'asset' => array(
        'baseUrl' => '/assets/'
    ),
    // 定义'模块1'的资源选项
    'mod1.asset' => array(
        'baseUrl' => '/assets/mod1/'
    ),
    // 定义'模块2'的资源选项
    'mod2.asset' => array(
        'baseUrl' => '/assets/mod2/'
    )
));

// 输出默认的资源地址
echo wei()->asset('style.css');

'/assets/style.css?v=1';

// 输出模块1的资源地址
echo wei()->mod1Asset('style.css');

'/assets/mod1/style.css?v=1';

// 输出模块2的资源地址
echo wei()->mod2Asset('style.css');

'/assets/mod2/style.css?v=1';
```

调用方式
--------

### 选项

名称                | 类型    | 默认值    | 说明
--------------------|---------|-----------|------
baseUrl             | string  | /         | 资源的基础URL地址
version             | string  | 1         | 自动附加到URL结尾的版本号码,如果要关闭,设置为`false`即可

### 方法

#### asset($file)
生成指定文件的资源URL地址