isRecordExists
==============

检查数据表是否存在指定的记录

案例
----

#### 检查主键为1的用户是否存在,和检查name为test的用户是否存在
```php
// 配置db服务
wei(array(
    'db' => array(
        'driver' => 'sqlite',
        'path' => ':memory:',
    )
));

$db = wei()->db;

$db->query("CREATE TABLE users (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(256) NOT NULL, PRIMARY KEY(id))");

$db->insert('users', array(
    'name' => 'twin',
    'address' => 'test'
));

$db->insert('users', array(
    'name' => 'test',
    'address' => 'test'
));


// 检查id为1的用户是否存在
if (wei()->isRecordExists('1', 'users')) {
    echo 'Yes';
} else {
    echo 'No';
}

// 检查name为test的用户是否存在
if (wei()->isRecordExists('test', 'users', 'name')) {
    echo 'Yes';
} else {
    echo 'No';
}

```

#### 运行结果
```php
'Yes'
'Yes'
```

调用方式
--------

### 选项

名称                | 类型    | 默认值                 | 说明
--------------------|---------|------------------------|------
table               | string  | 无                     | 记录所在的数据表
field               | string  | id                     | 要查找的字段名称

### 错误信息

名称                   | 信息
-----------------------|------
notFoundMessage        | %name%不存在
negativeMessage        | %name%已存在

### 方法

#### isRecordExists($input, $table, $field = 'id')
检查数据表是否存在指定的记录

相关链接
--------

* [验证器概览](../book/validators.md)