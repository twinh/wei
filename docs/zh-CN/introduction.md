# Widget - PHP微框架

Widget是一个PHP微框架,她提供了上百个高效简洁自由的接口,让您更方便地使用PHP.

Widget的使用比任何框架都要简单,只需3步,加载=>创建=>调用!

```php
// 加载核心类文件
require 'path/to/widget/lib/Widget/Widget.php';

// 创建微件管理器对象
$widget = widget();

// 调用query微件,获取URL请求中id参数
$id = $widget->query('id');
```