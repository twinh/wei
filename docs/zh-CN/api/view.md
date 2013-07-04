View
====

渲染指定名称的模板或获取视图微件对象

案例
----

### 渲染带布局的模板`template.php`
```php
// 设置模板所在的目录
widget()->view->setDirs(__DIR__ . '/fixtures');

// View微件将先渲染`template.php`文件,再把渲染结果存储到变量`$content`中,再渲染layout.php文件,并输出运行结果
echo widget()->view->render('template.php');
```

文件`template.php`

```php
<?php $this->layout('layout.php') ?>
Template Content
```

文件`layout.php`

```php
Layout Header
<?= $content ?>
Layout Footer
```

#### 运行结果
```php
'Layout Header
Template Content
Layout Footer'
```

调用方式
--------

### 选项

| 名称                | 类型    | 默认值    | 说明              |
|---------------------|---------|-----------|-------------------|
| vars                | array   | 无        | 模板变量          |
| dirs                | array   | 无        | 模板所在的目录    |
| extension           | string  | .php      | 默认的模板扩展名  |

### 方法

#### view($name, $vars = array())
渲染指定名称的模板

#### view()
获取视图微件对象

#### view->render($name, $vars = array())
渲染指定名称的模板,同`view($name, $vars = array())`

#### view->display($name, $vars = array())
渲染并输出指定名称的模板

#### view->assign($name, $value)
设置视图变量

#### view->get($name)
获取视图变量

#### view->getFile()
获取模板文件

#### view->layout($name, $variable = 'content')
设置模板的父模板名称

#### view->setDirs($dirs)
设置模板所在的目录
