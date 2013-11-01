Config
======

配置数据管理

案例
----

### 设置和获取配置

```php
// 设置配置项'title'的值为'Widget Documentation'
wei()->setConfig('title', 'Widget Documentation');

// 输出配置项'title'的值
echo wei()->getConfig('title');
```

### 批量设置配置数据

```php
wei()->setConfig(array(
	'yesOrNo' => array(
        'y' => '是',
        'n' => '否'
    ),
    'gender' => array(
        'm' => '男',
        'f' => '女'
    ),
    'priorities' => array(
    	-10 => '低',
    	0 	=> '中',
    	10 	=> '高'
    ),
	'statuses' => array(
		'draft' 	=> '草稿箱',
		'inProcess'	=> '进行中',
		'done' 		=> '完成',
	)
));
```

> #### 注意
> 
> wei()->setConfig($array) 等同于 wei($array);

### 输出HTML option标签

```php
echo wei()->config->toOptions('yesOrNo');

// 输出
'<option value="y">是</option><option value="n">否</option>';
```

### 输出JSON数据

```php
echo wei()->config->toJson('yesOrNo');

// 输出
'{"y":"\u662f","n":"\u5426"}';
```

调用方式
--------

### 选项

*无*

### 方法

#### widget->setConfig($name, $default = null)
设置配置

#### widget->getConfig($name, $value)
获取配置

#### config->toJson($name)
转换配置为JSON字符串

#### config->toOptions($name)
转换配置为HTML下拉菜单选项
