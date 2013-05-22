Widget
======

微件管理器,用于获取微件对象,设置自动加载,设置别名,设置配置等

案例
----

### 获取微件对象
```php
$widget = widget();

// 上面
$widget = \Widget\Widget::create();
```
调用方式
--------

### 选项

名称        | 类型   | 默认值        | 说明
------------|--------|---------------|------
config      | array  | 无            | 所有微件的配置选项
inis        | array  | 无            | PHP的ini配置选项 
autoloadMap | array  | 无            | 自动加载的命名空间和路径地址
autoload    | bool   | true          | 是否启用自动加载
alias       | array  | 无            | 微件别名列表
preload     | array  | array('is')   | 预加载的微件列表
import      | array  | 无            | 导入指定目录下的微件类

### 方法

TODO