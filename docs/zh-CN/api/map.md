Map
===

映射数据管理

案例
----

### 配置映射文件的路径

假设文件`config/map.php`的内容如下

```php
return array(
	'yesOrNo' => array(
        'y' => '是',
        'n' => '否'
    ),
    'gender' => array(
        'm' => '男',
        'f' => '女'
    ),
	'statuses' => array(
		'draft' 	=> '草稿箱',
		'inProcess'	=> '进行中',
		'done' 		=> '完成',
	)
);
```

指定map文件路径

```php
widget(array(
	'map' => array(
		'file' => 'config/map.php'
	)
));
```

### 获取映射列表

```php
$gender = widget()->map('gender');

// 返回
$gender = array(
	'm' => '男',
    'f' => '女'
);
```

### 获取执行项的映射列表的值

```php
// 输出"女"
echo widget()->map('gender', 'f');
```

### 输出HTML option标签

```php
echo widget()->map->toOptions('yesOrNo');

// 输出
'<option value="y">是</option><option value="n">否</option>'
```

### 输出JSON数据

```php
echo widget()->map->toJson('yesOrNo');

// 输出
'{"y":"\u662f","n":"\u5426"}'
```

调用方式
--------

### 选项

名称    | 类型    | 默认值                           | 说明
--------|---------|----------------------------------|------
file    | string  | 无                               | map文件所在的路径

### 方法

#### map($name)

#### map($name, $key)

#### map->get($name)

#### map->toJson($name)

#### map->toOptions($name)