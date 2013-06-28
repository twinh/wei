# 安装

## 下载

Widget支持Composer下载和直接下载源文件两种安装方式.

如果您的开发环境支持命令行,并且具有网络链接,推荐您通过Composer下载,因为Composer可以帮您处理不同框架类库之间的依赖关系.
否则,您也可以直接下载源文件使用.

### 通过Composer下载

如果您还不了解PHP最流行的依赖管理器,请速度点我[http://getcomposer.org/](http://getcomposer.org/)补习下功课^_^

1. 进入你的项目目录,下载Composer

```
$ curl -sS https://getcomposer.org/installer | php
```

2. 在根目录下创建或更新您的composer.json文件

```
{
    "require": {
        "widget/widget": "0.9.3-RC1"
    }
}
```

3. 执行安装命令,耐心等待到安装结束

```
php composer.phar install
```

至此,您可以看到根目录下增加了`vendor`目录,安装完成.

### 直接下载源文件

**稳定版**

敬请等待

**开发尝鲜版**

https://github.com/twinh/widget/archive/master.zip

更多版本请前往GitHub查看 [https://github.com/twinh/widget/tags](https://github.com/twinh/widget/tags)