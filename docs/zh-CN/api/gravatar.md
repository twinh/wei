Gravatar
========

根据邮箱地址生成自定义大小的Gravatar头像

案例
----

### 生成默认大小(80px)的Gravatar头像
```php
echo '<img src="' . wei()->gravatar('twinhuang@qq.com') . '" />';
```

#### 页面显示效果
<img src="http://www.gravatar.com/avatar/0a9990183df83793208efa067136f8d3?s=80&d=mm" />

### 生成预设定大小的Gravatar头像
```php
// 输出较小尺寸(48px)的Gravatar头像
echo '<img src="' . wei()->gravatar->small('twinhuang@qq.com') . '" />';

// 输出较大尺寸(200px)的Gravatar头像
echo '<img src="' . wei()->gravatar->large('twinhuang@qq.com') . '" />';
```

#### 页面显示效果
<img src="http://www.gravatar.com/avatar/0a9990183df83793208efa067136f8d3?s=48&d=mm" />

<img src="http://www.gravatar.com/avatar/0a9990183df83793208efa067136f8d3?s=200&d=mm" />

### 自定义默认图片
```php
wei(array(
    'gravatar' => array(
        'default' => 'http://i2.wp.com/a248.e.akamai.net/assets.github.com/images/gravatars/gravatar-user-420.png'
    )
));

echo '<img height="80" src="' . wei()->gravatar('twinhuang@example.com') . '" />';
```

#### 页面显示效果
<img height="80" src="http://www.gravatar.com/avatar/ea58edaf7cced7ef81b06b14aeadb625?s=80&d=http%3A%2F%2Fi2.wp.com%2Fa248.e.akamai.net%2Fassets.github.com%2Fimages%2Fgravatars%2Fgravatar-user-420.png" />

调用方式
--------

### 选项

名称      | 类型   | 默认值 | 说明
----------|--------|--------|------
default   | string | mm     | 当邮箱没有对应的Gravatar头像时显示的图片,可以是预设的图片类型(包括`404`, `mm`, `identicon`, `monsterid`或`wavatar`),也可以自定义的图片地址
secure    | bool   | false  | 是否输出HTTPS协议的图片地址
size      | int    | 80     | 默认的头像图片大小
smallSize | int    | 48     | 自定义的小头像图片大小
largeSize | int    | 200    | 自定义的大头像图片大小

### 方法

#### gravatar($email, $size = null, $default = null, $rating = null)
根据邮箱地址生成自定义大小的Gravatar头像

#### gravatar->small($email, $default = null, $rating = null)
根据邮箱地址生成较小尺寸(48px)的Gravatar头像

#### gravatar->large($email, $default = null, $rating = null)
根据邮箱地址生成较大尺寸(200px)的Gravatar头像
