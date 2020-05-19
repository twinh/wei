Wei
===

对象管理器,所有对象的入口.

用于获取服务对象,设置自动加载,设置别名,设置配置等.

案例
----

### 获取对象管理器

```php
/* @var $wei \Wei\Wei */
$wei = wei();
```

### 获取和调用服务

在微框架中,我们将提供功能的对象称为"服务",如缓存服务(cache),数据库服务(db).

获取服务对象使用`wei()->服务名称`即可.

```php
// 获取数据库操作服务
$db = wei()->db;

// 获取redis缓存服务
$redis = wei()->redis;

// 使用验证服务检查数据
$validator = wei()->validate(array());
```

### 设置服务配置

对象配置可以通过wei函数的第一个参数来设置,

完整配置请查看[配置](../book/configuration.md)章节.

```php
$wei = wei(array(
    // 类`Wei\Wei`的属性值
    'wei' => array(
        'debug' => true,
    ),
    // 类`Wei\Db`的属性值
    'db' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'dbname'    => 'wei',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => '123456',
    )
));
```

### 设置类自动加载

微框架支持[PRS-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)风格的类自动加载器.

```php
wei(array(
    'wei' => array(
        // 启用自动加载
        'autoload' => true,
        // 设置自动加载的类的命名空间和类所在的目录
        'autoloadMap' => array(
            '命名空间' => '类所在路径',
            'MyLib' => 'path/to/lib',
            'MyProject' => 'path/to/project',
            'MyClass\Module' => 'path/to/class',
            // 将未在上面指定命名空间的类,都引导到library目录下
            '' => 'path/to/library'
        )
    )
));

// 检查类是否正确加载

// => 检查文件 path/to/library/MyNamesapce/MyClass.php 是否存在
var_dump(class_exists('MyNamesapce\MyClass'));

// => 检查文件 path/to/library/MyClass/MyClass.php 是否存在
var_dump(class_exists('MyClass\MyClass'));
```

### 开启错误调试

错误调试可通过`debug`选项开启,PHP的调试可通过`inis`选项配置

**注意:** 两者的配置相互独立,不互相影响

```php
wei(array(
    'wei' => array(
        // 开启调试模式
        'debug' => true,
        // 通过PHP ini配置,开启PHP错误信息提示
        'inis' => array(
            // 在屏幕上输出错误信息
            'display_errors' => true,
            // 设置错误报告的级别
            'error_reporting' => -1,
        ),
    )
));
```

### 设置PHP ini配置

[PHP的配置](http://www.php.net/manual/zh/ini.php)可以通过`inis`选项来设置.

```php
wei(array(
    'wei' => array(
        'inis' => array(
            'date.timezone' => 'Asia/Shanghai', // 设置时区为上海
            'memory_limit'  => '128M',          // 设置最大限制为128M
            'post_max_size' => '8M',            // 设置POST请求的最大数据限制
            'session.name'  => 'PHPSESSID',     // 设置Session的cookie名称
            'ini配置名称'   => 'ini配置的值',
        )
    )
));
```

### 通过`aliases`选项加载自定义服务

除了系统自带的对象之外,你也可以自定义对象类.

1. 定义您的类,继承于`\Wei\Base`

    ```php
    namespace MyProject;

    class Application extends \Wei\Base
    {
        public function run()
        {
            // do something
        }
    }
    ```
2. 设置`aliases`选项,指定您的类所作为的服务名称

    ```php
    wei(array(
        'wei' => array(
            'aliases' => array(
                '服务名称' => '类名称',
                'app' => 'MyProject\Application'
            )
        )
    ));
    ```

3. 调用您的对象

    ```php
    // 获取自定义服务
    /* @var $app \MyProject\Application */
    $app = wei()->app;

    // 和平常一样调用类的方法
    $app->run();
    ```

### 通过`import`导入目录下的类为服务对象

```php
wei(array(
    'wei' => array(
        'import' => array(
            array(
                'dir' => '要导入的类文件所在的目录',
                'namespace' => '要导入的类文件所在的命名空间',
                'format' => '导入后服务的名称格式,%s将被替换为文件名称'
            ),
            array(
                'dir' => 'path/to/MyProject',
                'namespace' => 'MyProject',
                'format' => 'my%s'
            )
        ),
    )
));
```

### 区分`aliases`和`providers`选项

1. `aliases`选项数组的key是服务名称,value是类名称
2. `providers`选项数组的key是服务名称,value也是服务名称

```php
wei(array(
    'wei' => array(
        'aliases' => array(
            '服务名称' => '类名称'
        ),
        'providers' => array(
            '服务名称' => '服务名称'
        )
    )
));
```

调用方式
--------

### 选项

名称            | 类型     | 默认值        | 说明
----------------|----------|---------------|------
debug           | bool     | true          | 是否启用调试模式
inis            | array    | 无            | PHP的ini配置选项
autoload        | bool     | true          | 是否启用自动加载
autoloadMap     | array    | 无            | 自动加载的命名空间和路径地址
aliases         | array    | 无            | 服务别名列表
import          | array    | 无            | 导入指定目录下的对象类
preload         | array    | 无            | 预加载的服务列表
beforeConstruct | callbale | 无            | 每个对象初始化前的回调
afterConstruct  | callbale | 无            | 每个对象初始化后的回调

### 回调

#### beforeConstruct($wei, $full, $name)

名称        | 类型          | 说明
------------|---------------|------
$wei        | Wei\Wei       | 对象管理器
$full       | string        | 完整的对象名称,包含`.`连接符,如`db`,`user.db`
$name       | string        | 对象名称,不包含`.`连接符,如`db`,`request`

#### afterConstruct($wei, $full, $name, $object)

名称        | 类型          | 说明
------------|---------------|------
$wei        | Wei\Wei       | 对象管理器
$full       | string        | 完整的对象名称,包含`.`连接符,如`db`,`user.db`
$name       | string        | 对象名称,不包含`.`连接符,如`db`,`request`
$object     | Wei\Base      | 当前初始化的对象

### 方法

#### wei($config)
获取对象管理器,如果不存在,将创建一个新的对象

**返回:** `Wei\Wei` 对象管理器

**参数**

名称    | 类型         | 说明
--------|--------------|------
$config | string,array | 对象的配置数组或配置文件

#### wei()->isDebug()
检查是否启用了调试模式

**返回:** `bool`

#### wei()->setDebug($debug)
开启或关闭调试模式

**返回:** `Wei\Wei` 对象管理器

**参数**

名称    | 类型         | 说明
--------|--------------|------
$debug  | bool         | 是否启用调试

#### wei()->setConfig($name, $vlaue = array())
设置对象的配置

**返回:** `Wei\Wei` 对象管理器

**参数**

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$name       | string | 无            | 对象的名称,如`request`, `request.sub`
$value      | array  | 无            | 对象的配置选项

#### wei()->setConfig($array = array())
设置对象所有配置

**返回:** `Wei\Wei` 对象管理器

**参数**

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$array      | array  | 无            | 对象的完整配置

#### wei()->getConfig($name)
获取对象的选项配置

**返回:** `mixed` 配置的值,如果配置不存在,返回`null`

**参数**

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$name       | string | 无            | 配置的名称

#### wei()->get($name, $options = array(), $providers = array())
获取一个对象

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$name       | string | 无            | 对象的名称
$options    | array  | 无            | 除了会通过`config`方法获取配置选项之外的附加的配置选项
$providers       | array  | 无            | 指定对象的依赖关系

#### wei()->import($dir, $namespace, $format = null)
导入指定目录下的类文件

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$dir        | string | 无            | 类文件所在的目录
$namespace  | string | 无            | 类名对应的命名空间
$format     | string | 无            | 类文件的格式

#### wei()->newInstance($name, $options = array(), $providers = array())
初始化一个新的对象

#### wei()->set($name, wei())
设置对象

#### wei()->remove($name)
移除对象,如果对象存在,返回`true`,否则返回`false`

#### wei()->getClass($name)
根据对象名称获取对象类名

#### wei()->has($name)
检查对象是否存在

#### wei()->setAutoload($bool)
启用或禁用PSR-0类自动加载
