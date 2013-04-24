Browser
=======

获取浏览器的名称和版本

案例
----

### 打印出当前浏览器的名称和版本
```php
// 也可以通过 $widget->browser->getName(); 获取浏览器名称
echo $widget->browser();

echo $widget->browser->getVersion();
```

### 运行结果
```php

// 输出会因浏览器和版本的不同而不同

'chrome'

'26.0.1410.64'
```

调用方式
-------

### 选项

目前Browser除了`name`和`version`之外的选项均为公开属性,可以直接调用,如`$widget->browser->chrome`.

| 名称      | 类型      | 默认值    | 说明                                                                                  |
|-----------|-----------|-----------|---------------------------------------------------------------------------------------|
| chrome    | bool      | false     | 当用户使用谷歌浏览器时为true                                                          |
| webkit    | bool      | false     | 当用户使用谷歌或Safari浏览器时为true                                                  |
| opera     | bool      | false     | 当用户使用Opera浏览器时为true                                                         |
| msie      | bool      | false     | 当用户使用Internet Explorer浏览器时为true                                             |
| mozilla   | bool      | false     | 当用户使用火狐浏览器时为true                                                          |
| safari    | bool      | false     | 当用户使用Safari浏览器时为true                                                        |
| name      | string    | null      | 用户当前浏览器的名称,为上面选项的任何一个值,如chrome,msie,null表示无法识别浏览器名称  |
| version   | string    | null      | 用户当前浏览器的版本,同样null表示无法识别浏览器版本                                   |

### 方法

#### browser()
获取浏览器名称

#### browser->getName()
获取浏览器名称,与上一个方法行为一致

#### browser->getVersion()
获取浏览器版本

