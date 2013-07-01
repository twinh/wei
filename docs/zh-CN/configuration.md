# 配置

Widget提供了强大又简单的配置方式,您可以通过配置指定每个微件的选项值.

在Widget中,每个微件都有一个单独的配置数组,数组的键名表示微件的选项名称,数组的值表示微件的选项值.

下面我们通过一个完整的配置例子,了解Widget的配置方式.

### 一个完整的配置例子

```php
// 通过`widget`函数的第一个参数设置配置
$widget = widget(array(
    // 微件管理器的配置
    'widget' => array(
        'debug' => true,
        // ... 其他选项
    ),
    // 数据库微件的配置
    'db' => array(
        'dsn' => 'mysql:host=localhost;dbname=widget;charset=utf8',
        'user' => 'root',
        'password' => '123456',
    ),
    // 日志微件的配置
    'logger' => array(
        'dir'   => 'log',
    ),
    // Redis缓存微件的配置
    'redis' => array(
        'host' => '127.0.0.1',
        'port' => 6379
    ),
    // 视图微件的配置
    'view' => array(
        'dirs' => 'views'
    )
    // 更多微件配置...
));
```

通过上面的例子我们可以了解到:

1. 配置通过`widget`函数的第一个参数设置
2. 配置数组的第一级键名是微件名称,如上面例子中的`widget`,`db`,`redis`,`view`都是微件名称
3. 配置数组的第二级键名是微件的选项名称,如`db`微件的`user`选项,`logger`微件的`dir`选项
4. 每个微件都有多个 **选项** ,可以在[微件目录](README.md)查找到相应微件文档,在文档中查阅`选项`章节

## 设置和获取配置

Widget目前接受两种配置参数,一种是PHP数组,另一种是返回PHP数组的文件.

其他的类型的配置,可以查看最后的 **扩展** 章节了解

### 通过PHP数组加载配置

```php
$widget = widget(array(
    'widget' => array(
        'debug' => true,
    ),
    'db' => array(
        'dsn' => 'mysql:host=localhost;dbname=widget;charset=utf8',
        'user' => 'root',
        'password' => '123456',
    )
));
```

### 通过PHP文件加载配置

```php
$widget = widget('config/default.php');
```

文件`config/default.php`的内容

```php
// 定义一些常用变量或编写一些简单逻辑
$root = dirname(__DIR__);
$debug = true;

// 返回配置数组
return array(
    'widget' => array(
        'debug' => $debug
    ),
    'db' => array(
        'dsn' => 'mysql:host=localhost;dbname=widget;charset=utf8',
        'user' => 'root',
        'password' => '123456',
    ),
    'view' => array(
        'dirs' => $root
    )
);
```

### 获取配置

```php
$widget = widget();

// 获取数据库微件的配置数组
$db = $widget->config('db');

// 返回的数组如下
$db = array(
    'dsn' => 'mysql:host=localhost;dbname=widget;charset=utf8',
    'user' => 'root',
    'password' => '123456',
);

// 获取数据库微件配置中的`user`选项
$user = $widget->config('db\user');

// 返回的变量值如下
$user = 'root';
```

## 扩展

除了上面提到的PHP数组和PHP数组文件配置,我们可以通过简单的代码实现其他格式作为配置文件.

### 使用YAML作为配置文件

**TODO**

### 使用JSON作为配置文件

**TODO**

### 使用INI作为配置文件

**TODO**