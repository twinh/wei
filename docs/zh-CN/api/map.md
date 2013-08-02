Map
===

映射数据管理

案例
----

### 获取映射列表

```php
$statuses = widget()->map('statuses');
```

### 获取执行项的映射列表的值

```php
// 输出"草稿中"
echo widget()->map('statuses', 'draf');
```

### 输出HTML option标签

```php
// TODO
```

### 输出JSON数据

```php
// TODO
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