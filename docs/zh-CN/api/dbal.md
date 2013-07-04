Dbal
====

获取[Doctrine DBAL](https://github.com/doctrine/dbal)的数据库查询对象

案例
----

### 设置数据库类型并执行简单的查询
```php
widget()->config('dbal', array(
    'driver' => 'pdo_sqlite',
    'path' => 'test.sqlite'
));

$db = widget()->dbal();

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

### 创建读写(主备)分离的数据库查询对象
```php
// 添加微件别名
widget()->appendOption('alias', array(
    'dbalSlave' => 'Widget\DbAl'
));

// 设置数据库配置选项
widget()->config(array(
    'dbal' => array(
        'driver' => 'pdo_sqlite',
        'path' => 'test.sqlite'
    ),
    'dbalSlave' => array(
        'driver' => 'pdo_sqlite',
        'path' => 'slave.sqlite'
    )
));

// 获取主DB数据库对象
widget()->dbal();

// 获取备DB数据库对象
widget()->dbalSlave();
```

调用方式
--------

### 选项

完整的配置请查看Doctrine DBAL官方提供的文档
http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html

#### 以下三个主要选项,只要提供一个的值即可

| 名称          | 类型      | 默认值    | 说明                                                                                          |
|---------------|-----------|-----------|-----------------------------------------------------------------------------------------------|
| driver        | string    | 无        | 数据库驱动的类型,目前支持 `pdo_mysql`,`pdo_sqlite`,`pdo_pgsql`,`pdo_oci`,`pdo_sqlsrv`和`oci8` |
| driverClass   | string    | 无        | 驱动的类名                                                                                    |
| pdo           | \PDO      | 无        | 实例化的PDO对象                                                                               |

#### 当选项`driver`的值为`pdo_sqlite`时,需提供以下配置选项

| 名称          | 类型      | 默认值    | 说明                                                                                          |
|---------------|-----------|-----------|-----------------------------------------------------------------------------------------------|
| user          | string    | 无        | 连接数据库的用户名                                                                            |
| password      | string    | 无        | 连接数据库的密码                                                                              |
| path          | string    | 无        | 数据库存放的路径,与`memory`选项二选一,优先使用`path`选项                                      |
| memory        | bool      | 无        | 是否使用SQLite的内存数据库(非持久化,在每次请求完毕就自动删除)                                 |

#### 当选项`driver`的值为`pdo_mysql`时,需提供以下配置选项

| 名称          | 类型      | 默认值    | 说明                                                                                          |
|---------------|-----------|-----------|-----------------------------------------------------------------------------------------------|
| user          | string    | 无        | 连接数据库的用户名                                                                            |
| password      | string    | 无        | 连接数据库的密码                                                                              |
| host          | string    | 无        | 数据库的主机地址                                                                              |
| port          | int       | 无        | 连接数据库的端口                                                                              |
| dbname        | string    | 无        | 连接数据库的数据库名称                                                                        |
| unix_socket   | string    | 无        | 连接数据库的socket名称                                                                        |
| charset       | string    | 无        | 连接数据库的字符集                                                                            |

#### 当选项`driver`的值为`pdo_pgsql`时,需提供以下配置选项

| 名称          | 类型      | 默认值    | 说明                                                                                          |
|---------------|-----------|-----------|-----------------------------------------------------------------------------------------------|
| user          | string    | 无        | 连接数据库的用户名                                                                            |
| password      | string    | 无        | 连接数据库的密码                                                                              |
| host          | string    | 无        | 数据库的主机地址                                                                              |
| port          | int       | 无        | 连接数据库的端口                                                                              |
| dbname        | string    | 无        | 连接数据库的数据库名称                                                                        |

#### 当选项`driver`的值为`pdo_oci` / `oci8`时,需提供以下配置选项

| 名称          | 类型      | 默认值    | 说明                                                                                          |
|---------------|-----------|-----------|-----------------------------------------------------------------------------------------------|
| user          | string    | 无        | 连接数据库的用户名                                                                            |
| password      | string    | 无        | 连接数据库的密码                                                                              |
| host          | string    | 无        | 数据库的主机地址                                                                              |
| port          | int       | 无        | 连接数据库的端口                                                                              |
| dbname        | string    | 无        | 连接数据库的数据库名称                                                                        |
| charset       | string    | 无        | 连接数据库的字符集                                                                            |

#### 当选项`driver`的值为`pdo_sqlsrv`时,需提供以下配置选项

| 名称          | 类型      | 默认值    | 说明                                                                                          |
|---------------|-----------|-----------|-----------------------------------------------------------------------------------------------|
| user          | string    | 无        | 连接数据库的用户名                                                                            |
| password      | string    | 无        | 连接数据库的密码                                                                              |
| host          | string    | 无        | 数据库的主机地址                                                                              |
| port          | int       | 无        | 连接数据库的端口                                                                              |
| dbname        | string    | 无        | 连接数据库的数据库名称                                                                        |

### 方法

#### dbal()
获取数据库查询对象
