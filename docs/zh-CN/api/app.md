App
===

创建并运行一个MVC应用

案例
----

```php
// TODO
```

调用方式
--------

### 选项

名称                | 类型    | 默认值        | 说明
--------------------|---------|---------------|------
controllerFormat    | string  | Controller\%s | 控制器的类名格式,%s会被替换为类名
defaultController   | string  | index         | 默认的控制器名称
defaultAction       | string  | index         | 默认的行为名称

### 方法

#### app($options)
根据提供的选项,创建一个MVC应用,执行相应的行为方法,并输出视图数据

#### getController()
获取当前应用的控制器名称

#### setController($controller)
设置当前应用的控制器名称

#### getAction()
获取当前应用的行为名称

#### setAction($action)
设置当前应用的行为名称

#### getControllerClass($controller)
获取指定控制器的完整类名(不检查类名是否存在)

#### getDefaultTemplate()
获取当前控制器和行为对应的视图文件路径

#### preventPreviousDispatch()
中断当前的应用逻辑

#### forward($action, $controller = null)
中断当前的应用逻辑,并跳转到指定的控制器和行为