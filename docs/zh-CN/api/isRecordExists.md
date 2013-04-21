[isRecordExists()](http://twinh.github.io/widget/api/isRecordExists)
====================================================================

检查数据表是否存在指定的记录

### 
```php
bool isRecordExists( $input [, $table ] , $field ] ] )
```

##### 参数
* **$input** `mixed` 待验证的数据,一般为主键的值
* **$table** `string` 数据表的名称
* **$field** `string` 指定的字段名称,留空表示"id"


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notFound`            | %name%不存在                                                   |
| `negative`            | %name%已存在                                                   |


##### 代码范例
检查主键为1的用户是否存在,和检查name为test的用户是否存在
```php
<?php

// Set configuraion for db widget
$widget->config('db', array(
    'driver' => 'pdo_sqlite',
    'memory' => true
));

/* @var $db \Doctrine\DBAL\Connection */
$db = $widget->db();

$db->query("CREATE TABLE users (id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(256) NOT NULL, PRIMARY KEY(id))");

$db->insert('users', array(
    'name' => 'twin',
    'address' => 'test'
));

$db->insert('users', array(
    'name' => 'test',
    'address' => 'test'
));


// Check if the id=1 user exists
if ($widget->isRecordExists('1', 'users')) {
    echo 'Yes';
} else {
    echo 'No';
}

// Check if the name=test user exists
if ($widget->isRecordExists('test', 'users', 'name')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'YesYes'
```
