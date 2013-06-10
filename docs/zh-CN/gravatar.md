Gravatar
========

根据邮箱地址生成自定义大小的Gravatar头像

案例
----

### 生成默认大小(80px)的Gravatar头像
```php
echo '<img src="' . $widget->gravatar('twinhuang@qq.com') . '" />';
```

#### 页面显示效果
<img src="http://www.gravatar.com/avatar/0a9990183df83793208efa067136f8d3?s=80&d=mm" />

### 生成预设定大小的Gravatar头像
```php
// 输出较小尺寸(48px)的Gravatar头像
echo '<img src="' . $widget->gravatar->small('twinhuang@qq.com') . '" />';

// 输出较大尺寸(200px)的Gravatar头像
echo '<img src="' . $widget->gravatar->large('twinhuang@qq.com') . '" />';
```

#### 页面显示效果
<img src="http://www.gravatar.com/avatar/0a9990183df83793208efa067136f8d3?s=48&d=mm" />
<img src="http://www.gravatar.com/avatar/0a9990183df83793208efa067136f8d3?s=200&d=mm" />

调用方式
--------

### 选项

名称      | 类型   | 默认值 | 说明
----------|--------|--------|------
default   | string | mm     | 
secure    | bool   | false  |
size      | int    | 80     |
smallSize | int    | 48     |
largeSize | int    | 200    |

### 方法

#### gravatar($email, $size = null, $default = null, $rating = null)
根据邮箱地址生成自定义大小的Gravatar头像

#### gravatar->small($email, $default = null, $rating = null)
根据邮箱地址生成较小尺寸(48px)的Gravatar头像

#### gravatar->large($email, $default = null, $rating = null)
根据邮箱地址生成较大尺寸(200px)的Gravatar头像