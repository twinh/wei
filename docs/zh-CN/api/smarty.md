Smarty
======

渲染Smarty模板或获取原始Smarty对象

案例
----

### 渲染模板`hello.tpl`
```php
// 准备视图变量
$data = array(
    'name' => 'Widget'
);

// 输出渲染后的视图
echo widget()->smarty('hello.tpl', $data);
```

#### 文件`hello.tpl`的内容
```php
Hello {{$name}}
```

#### 输出结果
```php
'Hello Widget'
```

调用方式
--------

### 选项

| 名称                | 类型    | 默认值    | 说明                 |
|---------------------|---------|-----------|----------------------|
| extension           | string  | .tpl      | 默认的模板扩展名     |
| object              | \Smarty | 无        | Smarty对象           |
| options             | array   | 见下表    | Smarty对象的配置选项 |

#### `options`的默认值
```php
array(
    'template_dir'      => array(),
    'config_dir'        => array(),
    'plugins_dir'       => array(),
    'compile_dir'       => null,
    'cache_dir'         => null,
    'left_delimiter'    => '{',
    'right_delimiter'   => '}',
);
```

### 方法

#### smarty($name, $vars = array())
渲染指定名称的模板

#### smarty()
获取原始Smarty对象

#### smarty->render($name, $vars = array())
渲染指定名称的模板,同`smarty($name, $vars = array())`

#### smarty->display($name, $vars = array())
渲染并输出指定名称的模板

#### smarty->assign($name, $value)
设置视图变量

#### smarty->get($name)
获取视图变量
