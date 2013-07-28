Widget
======

微件管理器,所有微件对象的入口.

用于获取微件对象,设置自动加载,设置别名,设置配置等.

案例
----

### 获取微件管理器对象
```php
// 通过widget函数获取
$widget = widget();

// 完整的获取方法如下,您可以根据自己的编码习惯选择适合您的方式
$widget = \Widget\Widget::create();
```

### 设置微件配置

微件配置可以通过widget函数的第一个参数来设置,

完整配置请查看[配置](../configuration.md)章节.

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

### 设置类自动加载

Widget支持[PRS-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)风格的类自动加载器.

```php
widget(array(
    'widget' => array(
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

Widget的错误调试可通过`debug`选项开启,PHP的调试可通过`inis`选项配置

**注意:** 两者的配置相互独立,不互相影响

```php
widget(array(
    'widget' => array(
        // 通过PHP ini配置,开启PHP错误信息提示
        'inis' => array(
            // 在屏幕上输出错误信息
            'display_errors' => true,
            // 设置错误报告的级别
            'error_reporting' => -1,
        ),
        // 开启Widget的调试模式
        'debug' => true,
    )
));
```

### 通过`aliases`选项加载自定义微件

除了系统自带的微件之外,你也可以自定义微件类.

1. 定义您的类,继承于`\Widget\AbstractWidget`

    ```php
    namespace MyProject;

    class Application extends \Widget\AbstractWidget
    {
        public function run()
        {
            // do something
        }
    }
    ```
2. 设置`aliases`选项,指定您定义的类的微件名称

    ```php
    widget(array(
        'aliases' => array(
            '微件名称' => '类名称',
            'app' => 'MyProject\Application'
        )
    ));
    ```

3. 调用您的微件

    ```php
    // 获取自定义微件对象
    $app = widget()->app;

    // 和
    $app->run();
    ```

### 通过`import`选项导入目录下的自定义微件

```php
TODO
``

### 区分`aliases`和`deps`

1. `aliases`选项数组的key是微件名称,value是类名称
2. `deps`选项数组的key是微件名称,value也是微件名称

```php
widget(array(
    'widget' => array(
        'aliases' => array(
            '微件名称' => '类名称'
        ),
        'deps' => array(
            '微件名称' => '微件名称'
        )
    )
));
```

调用方式
--------

### 选项

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
config      | array  | 无            | 所有微件的配置选项
inis        | array  | 无            | PHP的ini配置选项
debug       | bool   | true          | 是否启用调试模式
autoloadMap | array  | 无            | 自动加载的命名空间和路径地址
autoload    | bool   | true          | 是否启用自动加载
alias       | array  | 无            | 微件别名列表
preload     | array  | array('is')   | 预加载的微件列表
import      | array  | 无            | 导入指定目录下的微件类

### 方法

#### widget($config, $name = 'default')
获取指定名称的微件管理器,如果不存在,将创建一个新的对象

**返回:** `Widget\Widget` 微件管理器对象

**参数**

名称    | 类型         | 说明
--------|--------------|------
$config | string,array | 微件的配置数组或配置文件
$name   | string       | 微件对象的名称

#### widget()->config($name, $vlaue = array())
设置微件的配置

**返回:** `Widget\Widget` 微件管理器对象

**参数**

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$name       | string | 无            | 微件的名称,如`request`, `request.sub`
$value      | array  | 无            | 微件的配置选项

#### widget()->config($array = array())
设置微件所有配置

**返回:** `Widget\Widget` 微件管理器对象

**参数**

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$array      | array  | 无            | 微件的完整配置

#### widget()->config($name)
获取微件的选项配置

**返回:** `mixed` 配置的值,如果配置不存在,返回`null`

**参数**

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$name       | string | 无            | 配置的名称

#### widget()->get($name, $options = array(), $deps = array())
获取一个微件对象

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$name       | string | 无            | 微件的名称
$options    | array  | 无            | 除了会通过`config`方法获取配置选项之外的附加的配置选项
$deps       | array  | 无            | 指定微件的依赖关系

#### widget()->import($dir, $namespace, $format = null)
导入指定目录下的微件类文件

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$dir        | string | 无            | 类文件所在的目录
$namespace  | string | 无            | 类名对应的命名空间
$format     | string | 无            | 类文件的格式

#### widget()->newInstance($name, $options = array(), $deps = array())
初始化一个新的微件对象

#### widget()->set($name, widget())
设置微件对象

#### widget()->remove($name)
移出微件对象,如果对象存在,返回`true`,否则返回`false`

#### widget()->getClass($name)
根据微件名称获取微件类名

#### widget()->has($name)
检查微件是否存在

#### widget()->setAutoload($bool)
启用或禁用PSR-0类自动加载
