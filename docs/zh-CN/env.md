Env
===

环境检测及根据不同环境加载不同的配置文件

案例
----

### 在Widget初始化时根据环境加载配置
```php
\Widget\Widget::create(array(
    'widget' => array(
        'preload' => array(
            'env' // 在预加载选项增加Env微件
        )
    ),
    // 配置Env微件的选项
    'env' => array(
        // 配置环境的目录,%env%会替换成当前的环境名称
        'configDir' => 'config/config_%env%.php'
    )
));
```

### 判断当前是否在开发环境
```php
if ($widget->env->inDev()) {
    // do something
}
```

调用方式
--------

### 选项

| 名称      | 类型      | 默认值                     | 说明                                                                      |
|-----------|-----------|----------------------------|----------------------------------------------------------------------------------------------------------------|
| env       | string    | 无                         | 当前所在的环境,留空则判断$_SERVER['APPLICATION_ENV']的值,如果$_SERVER['APPLICATION_ENV']未定义,则为`prod`环境  |                 |
| configDir | string    | config/config_%env%.php    | 配置环境的目录,%env%会替换成当前的环境名称                                                                     |

### 方法

#### env()
获取环境名称

#### env->getEnv()
获取环境名称

#### env->inDev()
检查是否为开发环境

#### env->inTest()
检查是否为测试(Beta,演示)环境

#### env->inProd()
检查是否为生产(线上)环境