# Widget的配置

Widget的配置

Widget的配置与每一个微件类是紧密关联的,每一项配置都是有

Widget的配置与用过的大部分框架不同.在大部分框架中,配置只是一个
在Widget中,配置不再是无意义的一串字符,通过,每一项配置对应一个微件类的选项

不再是无意义

下面我们通过一个完整的配置例子来了解Widget的配置.

Widget目前接受两种配置参数,一种是PHP文件,另一种是PHP数组.
其他的类型的配置,可以自行转换成PHP数组后再


`widget`函数的第一个参数接收一个PHP文件或PHP数组作为配置


### 一个完整的配置例子

```php
// 通过`widget`函数的第一个参数设置配置
$widget = widget(array(
    // 微件管理器的配置
    'widget' => array(
        'debug' => true,
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
        // ...
    ),
    // 视图微件的配置
    'view' => array(
        'dirs' => 'views'
    )
    // ...
));
```

* 通过`widget`函数的第一个参数设置配置
* 配置数组的第一级键名是微件名称,如上面例子中的`widget`,`db`,`redis`,`view`都是微件名称
* 配置数组的第二级键名是微件的选项名称,如`db`微件的`user`选项,`logger`微件的`dir`选项
* 每个微件都有多个**选项**,可以在[微件目录](README.md)查找到相应微件文档,在文档中查阅`选项`章节

## 设置和获取配置

### 通过PHP数组加载配置

```php
$widget = widget(array(
    'widget' => array(
        ''
    ),
    'db' => array()
));
```

### 通过PHP文件加载配置

```php
$widget = widget('config/default.php');
```

文件`config/default.php`的内容

```php
// 定义一些常用变量
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

// 获取数据库的配置数组
$db = $widget->config('db');
```

## 深入了解配置

### 配置与微件

### 配置与微件选项

## 扩展 - 使用其他格式配置

Widget默认支持PHP数组和PHP数组文件配置.除此之外,我们还可以使用其他格式作为配置文件.

### 使用YAML作为配置文件

**TODO**

### 使用JSON作为配置文件

**TODO**

### 使用INI作为配置文件

**TODO**