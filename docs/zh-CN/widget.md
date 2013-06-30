Widget
======

微件管理器,所有微件类的入口.

用于获取微件对象,设置自动加载,设置别名,设置配置等.

案例
----

### 获取微件管理器对象
```php
$widget = widget();

// 完整的获取方法如下,您可以根据自己的编码习惯选择适合您的方式
$widget = \Widget\Widget::create();
```

### 设置自动加载

TODO

### 开启错误调试

TODO

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

#### widget()->config($name, $vlaue = array())
设置微件的选项配置

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
$name       | string | 无            | 微件的名称,如`request`, `request.sub`
$value      | array  | 无            | 微件的配置选项

#### widget()->config($name)
获取微件的选项配置

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
