# 配置

Widget提供了强大又简单的配置方式,您可以通过配置指定每个对象的选项值.

在Widget中,每个对象都有一个单独的配置数组,数组的键名表示对象的选项名称,数组的值表示对象的选项值.

下面我们通过一个完整的配置例子,了解Widget的配置方式.

### 一个完整的配置例子

```php
// 通过`widget`函数的第一个参数设置配置
$widget = widget(array(
    // 对象管理器的配置
    'widget' => array(
        'debug' => true,
        // ... 其他选项
    ),
    // 数据库对象的配置
    'db' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'dbname'    => 'widget',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => '123456',
    ),
    // 备机数据库对象的配置
    'slave.db' => array(
        'driver'    => 'mysql',
        'host'      => 'slave-host',
        'dbname'    => 'widget',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => '123456',
    ),
    // 日志对象的配置
    'logger' => array(
        'dir'   => 'log',
    ),
    // Redis缓存对象的配置
    'redis' => array(
        'host' => '127.0.0.1',
        'port' => 6379
    ),
    // 视图对象的配置
    'view' => array(
        'dirs' => 'views'
    )
    // 更多对象配置...
));
```

通过上面的例子我们可以了解到:

1. 配置通过`widget`函数设置
2. 配置数组的第一级键名是对象名称,如上面例子中的`widget`,`db`,`redis`,`view`都是对象名称
3. 配置数组的第二级键名是对象的选项名称,如`db`对象的`user`选项,`logger`对象的`dir`选项
4. 每个对象都有多个 **选项** ,可以在[对象目录](README.md)查找到相应对象文档,在文档中查阅`选项`章节

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
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'dbname'    => 'widget',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => '123456',
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
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'dbname'    => 'widget',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => '123456',
    ),
    'view' => array(
        'dirs' => $root
    )
);
```

### 获取配置

```php
$widget = widget();

// 获取数据库对象的配置数组
$db = $widget->getConfig('db');

// 返回的数组如下
$db = array(
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'dbname'    => 'widget',
    'charset'   => 'utf8',
    'user'      => 'root',
    'password'  => '123456',
);

// 获取数据库对象配置中的`user`选项
// 注意,不同级别的配置名称应该通过冒号":"隔开
$user = $widget->getConfig('db:user');

// 返回的变量值如下
$user = 'root';
```

### 配置多个类对象(如:连接到多个数据库)

在微框架中,要为一个对象配置多个实例,只需添加一个新的配置项即可.

配置项命名规则为`自定义名称.对象名称`,如`slave.db`,`slave`表示业务名称,按需命名,`db`表示数据库对象,两者通过`.`连接

在添加了该配置后,微框架会 **自动** 进行映射,可以通过`$widget->slaveDb`获取到`slave.db`配置的数据库对象

下面的例子展示了多个数据库的配置和数据库对象的获取.

```php
$widget = widget(array(
    // 默认数据库对象的配置
    'db' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'dbname'    => 'widget',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => '123456',
    ),
    // 备机数据库对象的配置
    'slave.db' => array(
        'driver'    => 'mysql',
        'host'      => 'slave-host',
        'dbname'    => 'widget',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => '123456',
    ),
    // 日志数据库对象的配置
    'logger.db' => array(
        'driver'    => 'mysql',
        'host'      => 'logger-host',
        'dbname'    => 'widget',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => '123456',
    )
));

// 获取默认数据库对象
$db = $widget->db;

// 获取备机数据库对象
$slaveDb = $widget->slaveDb;

// 获取日志数据库对象
$loggerDb = $widget->loggerDb;

// 使用日志数据库对象查询用户编号为1的操作日志
$loggerDb->findAll('userLog', array('userId' => 1));
```

## 扩展

除了上面提到的PHP数组和PHP数组文件配置,我们可以通过简单的代码实现其他格式作为配置文件.

### 使用YAML作为配置文件

[查看演示案例](../../../demos/using-yaml-as-widget-configuration)

### 使用JSON作为配置文件

[查看演示案例](../../../demos/using-json-as-widget-configuration)