Ua
==

检测客户端浏览器,操作系统和设备是否为指定的名称和版本

案例
----

### 根据用户设备跳转到相应的页面
```php
if (widget()->ua->inIphone()) {
    // 跳转到iPhone定制页面
} elseif (widget()->ua->inIpad()) {
    // 跳转到iPad定制页面
} else {
    // 展示默认页面
}

```

### 获取Windows Phone手机用户的版本
```php
echo widget()->ua->getVersion('WindowsPhone');
```

### 如果用户为IE浏览器,输出用浏览器版本
```php
if (widget()->ua->inIe()) {
    echo widget()->ua->getVersion('ie');
}
```

### 运行结果
```php
'10.0'
```

调用方式
-------

### 选项

*无*

### 方法

#### ua($name)
检测客户端是否为指定的浏览器,操作系统和手持设备

#### ua->in($name)
同`ua($name)`

#### ua->getVersion($name)
获取客户端浏览器,操作系统或手持设备的版本

#### ua->inMobile()
检查用户是否通过移动设备访问

#### ua->inIe()
检查用户是否通过IE浏览器访问

#### ua->inChrome()
检查用户是否通过Chrome浏览器访问

#### ua->inFirefox()
检查用户是否通过Firefox浏览器访问

#### ua->inIos()
检查用户是否使用苹果iOS系统

#### ua->inAndroid()
检查用户是否使用谷歌安卓系统

#### ua->inWindowsPhone()
检查用户是否使用微软Windows Phone系统

#### ua->inIphone()
检查用户是否使用iPhone/iPod访问网站

#### ua->inIpad()
检查用户是否使用iPad访问网站
