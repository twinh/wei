[view()](http://twinh.github.io/widget/api/view)
================================================

渲染指定名称的模板或获取视图微件对象

##### 目录
* view( [$name [, $vars ] ] )
* view()

### 
```php
string view( [$name [, $vars ] ] )
```

##### 参数
* **$name** `string` 模板文件的路径
* **$vars** `array` 附加到模板的变量


在下面的例子中,View微件将先渲染`template.php`文件,再把渲染结果存储到变量`$content`中,再渲染layout.php文件,并输出运行结果

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


##### 代码范例
渲染带布局的模板`template.php`
```php
<?php

$widget->view->setDirs(__DIR__ . '/fixtures');

echo $widget->view->render('template.php');
```
##### 运行结果
```php
'Layout Header
Template Content
Layout Footer'
```
- - - -

### 获取视图微件对象
```php
\Widget\View view()
```

##### 参数
*无*

