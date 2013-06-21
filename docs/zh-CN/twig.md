Twig
====

渲染Twig模板或获取Twig_Environment对象

案例
----

### 渲染模板`hello.html.twig`
```php
// 设置Twig模板目录
widget()->config('twig', array(
    'paths' => __DIR__
));

// 准备视图变量
$data = array(
    'name' => 'Widget'
);

// 输出渲染后的视图
echo widget()->twig('hello.twig.html', $data);
```

#### 文件`hello.html.twig`的内容
```php
Hello {{name}}
```

#### 输出结果
```php
'Hello Widget'
```

调用方式
--------

### 选项

| 名称                | 类型             | 默认值     | 说明                   |
|---------------------|------------------|------------|------------------------|
| extension           | string           | .html.twig | 默认的模板扩展名       |
| object              | Twig_Environment | 无         | Twig对象               |
| paths               | array,string     | 无         | 模板所在的目录         |
| envOptions          | array            | 见下表     | 创建Twig对象的配置选项 |

#### `envOptions`的默认值
```php
array(
    'debug'                 => false,
    'charset'               => 'UTF-8',
    'base_template_class'   => 'Twig_Template',
    'strict_variables'      => false,
    'autoescape'            => 'html',
    'cache'                 => false,
    'auto_reload'           => null,
    'optimizations'         => -1
);
```

### 方法

#### twig($name, $vars = array())
渲染指定名称的模板

#### twig()
获取twig对象

#### twig->render($name, $vars = array())
渲染指定名称的模板,同`twig($name, $vars = array())`

#### twig->display($name, $vars = array())
渲染并输出指定名称的模板

#### twig->assign($name, $value)
设置视图变量

#### twig->get($name)
获取视图变量
