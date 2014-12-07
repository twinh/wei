# 安装

Wei支持通过[Composer](https://getcomposer.org)下载和直接下载源文件两种安装方式.

如果您的开发环境支持命令行,并且具有网络链接,推荐您通过Composer下载.

因为Composer可以帮您处理不同框架类库之间的依赖关系.

否则,您也可以直接下载源文件使用.

## 通过Composer下载

1. 进入您的项目目录,下载Composer

    ```sh
    $ curl -sS https://getcomposer.org/installer | php
    ```

2. 在根目录下创建或更新您的composer.json文件

    ```json
    {
        "require": {
            "wei/wei": "0.9.15"
        }
    }
    ```

3. 执行安装命令,耐心等待到安装结束

    ```sh
    $ php composer.phar install
    ```

至此,您可以看到根目录下增加了`vendor`目录,安装完成.

## 直接下载源文件

### 稳定版

Zip文件下载: https://github.com/twinh/wei/archive/v0.9.15.zip

### 开发尝鲜版

Zip文件下载: https://github.com/twinh/wei/archive/master.zip

### 更多历史版本

更多历史版本请前往GitHub查看 https://github.com/twinh/wei/releases
