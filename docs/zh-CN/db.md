Db
==

获取[Doctrine DBAL](https://github.com/doctrine/dbal)的数据库查询对象

案例
----

### 设置数据库类型并执行简单的查询
```php
$widget->config('db', array(
    'driver' => 'pdo_sqlite',
    'path' => 'test.sqlite'
));

$db = $widget->db();

print_r($db->fetchAll("SELECT MAX(1, 2)"));
```

### 返回结果
```
Array
(
    [0] => Array
        (
            [MAX(1, 2)] => 2
        )

)
```

调用方式
--------

### 选项

完整的配置请查看Doctrine DBAL官方提供的文档
http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html

### 方法

#### db()
获取数据库查询对象
