Env
===

环境检测及根据不同环境加载不同的配置文件

案例
----

### 在初始化时根据环境加载配置

```php
$wei = wei(array(
    'wei' => array(
        'preload' => array(
            'env' // 在预加载选项增加env对象
        )
    ),
    // 配置env对象的选项
    'env' => array(
        // 配置文件的路径,%env%会替换成当前的环境名称
        'configFile' => 'config/config-%env%.php'
    )
));
```

### 判断当前是否在开发环境

```php
if (wei()->env->isDev()) {
    // do something
}
```

调用方式
--------

### 选项

名称       | 类型      | 默认值                      | 说明
-----------|-----------|-----------------------------|------
env        | string    | 无                          | 当前环境名称
detector   | callable  | 无                          | 检测环境名称的回调结构
ipMap      | array     | array('127.0.0.1' => 'dev') | 服务器IP地址与环境名称的对应列表,键名为服务器IP地址,值为环境名称
configFile | string    | config/config-%env%.php     | 配置环境的目录,`%env%`会替换成当前的环境名称

#### 环境名称的检测顺序

1. 如果用户设置了`env`选项,使用`env`作为环境名称
2. 如果用户设置了回调结构`detector`选项,调用`detector`获取返回值作为环境名称
3. 如果当前的服务器IP地址在`ipMap`的键名之中,使用对应的值作为环境名称
4. 使用`prod`作为环境名称

### 方法

#### env()
获取环境名称

#### env->getEnv()
获取环境名称

#### env->is($env)
检查是否为指定的环境名称

#### env->isDev()
检查是否为开发环境

#### env->isTest()
检查是否为测试(Beta,演示)环境

#### env->isProd()
检查是否为生产(线上)环境

#### env->setName($name)
设置环境名称

#### env->getName()
获取当前环境名称