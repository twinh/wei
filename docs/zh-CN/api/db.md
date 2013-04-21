[db()](http://twinh.github.io/widget/api/db)
============================================

获取[Doctrine DBAL](https://github.com/doctrine/dbal)的Connection对象

### 
```php
\Doctrine\DBAL\Connection db()
```

##### 参数
*无*


Db微件是对[Doctrine DBAL](https://github.com/doctrine/dbal)的简单封装,主要用于按需加载数据库查询对象.

完整的配置请查看Doctrine DBAL官方提供的文档
http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html


##### 代码范例
设置数据库类型并执行简单的查询
```php
<?php

$widget->config('db', array(
    'driver' => 'pdo_sqlite',
    'path' => 'test.sqlite'
));

$db = $widget->db();

print_r($db->fetchAll("SELECT MAX(1, 2)"));
```
##### 运行结果
```php
'Array
(
    [0] => Array
        (
            [MAX(1, 2)] => 2
        )

)
'
```
