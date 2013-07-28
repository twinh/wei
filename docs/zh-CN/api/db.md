Db
==

基于PDO的数据库操作微件,支持基本的增删查改(CRUD)和流行的Active Record模式的数据库操作.

目前支持`MySQL`,`SQLite`和`PostgreSQL`数据库.

案例
----

### 增删查改(CRUD)操作
```php
// 插入数据
widget()->db->insert('user', array(
    'username' => 'twin',
    'createdAt' => date('Y-m-d H:i:s') 
));

// 更新数据
widget()->db->update(
    'user', 
    array('username' => 'twin'), 
    array('id' => '1')
);

// 删除数据
widget()->db->delete('user', array('id' => '1'));

// 查找id为1的用户
widget()->db->select('user', 1);

// 查找所有分组编号为1的用户
widget()->db->selectAll('user', array('groupId' => '1'));
```

### Active Record模式

Active Record模式是将数据表的每一行映射为一个对象,数据表的字段与对象的属性一一对应.
完整的介绍请查看维基百科的说明[Active Record](http://zh.wikipedia.org/wiki/Active_Record)

#### 创建记录并保存
```php
// 创建一个新的用户记录对象
/* @var $user \Widget\Db\Record */
$user = widget()->db->user;

// 或是通过`create`方法创建
$user = widget()->db->create('user', array('groupId' => 1));

// 设置对象的值
$user->username = 'twin';
$user->createdAt = date('Y-m-d H:i:s');

// 保存到数据库中
$user->save();
```

#### 查找并更新记录数据
```php
// 查找主键为1的用户
$user = widget()->db->find('user', '1');

// 或是通过魔术方法更自然的获取对象
$user = widget()->db->user(1);

// 更新对象的值
$user->username = 'twin';

// 保存到数据库中 
$user->save();
```

#### 删除记录
```php
// 查找主键为1的用户
$user = widget()->db->user(1);

// 删除该记录
$user->delete();
```

### 连接到多个数据库

```php
$widget = widget(array(
    // 数据库微件的配置
    'db' => array(
        'dsn' => 'mysql:host=localhost;dbname=widget;charset=utf8',
        'user' => 'root',
        'password' => '123456',
    ),
    // 备机数据库微件的配置
    'slave.db' => array(
        'dsn' => 'mysql:host=slave-host;dbname=widget;charset=utf8',
        'user' => 'root',
        'password' => '123456',
    ),
    // 日志数据库微件的配置
    'logger.db' => array(
        'dsn' => 'mysql:host=logger-host;dbname=widget;charset=utf8',
        'user' => 'root',
        'password' => '123456',
    )
));

// 获取默认数据库微件
$db = $widget->db;

// 获取备机数据库微件
$slaveDb = $widget->slaveDb;

// 获取日志数据库微件
$loggerDb = $widget->loggerDb;

// 使用日志数据库微件查询用户编号为1的操作日志
$loggerDb->findAll('userLog', array('userId' => 1));
```

### 区分`fetch`,`select`和`find`方法

在db微件,`fetch`,`select`和`find`方法都是用于查询数据库数据,他们共同的特征是只返回第一行数据,不同点在于:

* `fetch`方法的第一个参数是完整的SQL语句,返回的是数组
* `select`方法的第一个参数是数据表名称,返回的是数组
* `find`方法的第一个参数也是数据表名称,但返回的是`Widget\Db\Record`对象

另外,如果要查询多条数据,对应的方法是`fetchAll`,`selectAll`和`findAll`.

```php
$db = widget()->db;

// 根据SQL语句查询一行记录,返回一个一维数组
$array = $db->fetch("SELECT * FROM user WHERE id = 1");

// 根据表名和条件查询一行记录,返回一个一维数组
$array = $db->select('user', 1);

// 根据表名和条件查询一行记录,返回一个`Widget\Db\Record`对象
$record = $db->find('user', 1);

// 根据SQL语句查询多行记录,返回一个二维数组
$array = $db->fetchAll("SELECT * FROM user WHERE groupId = 1");

// 根据表名和条件查询多行记录,返回一个二维数组
$array = $db->select('user');

// 根据表名和条件查询多行记录,返回一个`Widget\Db\Collection`对象
$collection = $db->find('user');
```

### 所有查询方法的参数和返回值类型比较

方法        | 参数                | 返回值类型
------------|---------------------|------------
select      | $table, $conditions | 数组
selectAll   | $table, $conditions | 二维数组
fetch       | $sql                | 数组
fetchAll    | $sql                | 二维数组
fetchColumn | $sql                | 字符串,返回指定栏的值
find        | $table, $conditions | Widget\Db\Record对象
findAll     | $table, $conditions | Widget\Db\Collection对象
query       | $sql                | PDOStatement对象

### 通过beforeQuery记录SQL日志
```php
$widget = widget(array(
    'db' => array(
        'beforeQuery' => function($sql, $params, $types, $db) {
            $log = $sql . "\n" . var_export($params, true);
            // 通过db微件获取logger微件,并调用debug方法记录SQL日志
            widget()->logger->debug($log);
        }
    )
));

$widget->db->query("SELECT DATE('now')");

// 日志文件内容
//[2013-07-10 23:15:38] widget.DEBUG: SELECT DATE('now')
//array (
//)
```

### SQL查询构建器

如果增删查改(CRUD)操作和Active Record模式还不能满足您的需求,你可以尝试使用QueryBuilder来生成更复杂的SQL语句

[查看QueryBuilder](queryBuilder.md)

调用方式
--------

### 选项

名称            |  类型    | 默认值               | 说明
----------------|----------|----------------------|------
user            | string   | 无                   | 连接数据库的用户名
password        | string   | 无                   | 连接数据库的密码
dsn             | string   | sqlite::memory:      | 数据源名称(Data Source Name),详细配置请查看下表
recordClass     | string   | Widget\Db\Record     | 记录类的基础类名称
collectionClass | string   | Widget\Db\Collection | 记录集合类的基础类名称
recordClasses   | array    | array()              | 自定义记录类的数组,键名为数据表名称,值为记录类名称
recordNamespace | string   | 无                   | 自定义记录类的命名空间
beforeQuery     | callback | 无                   | 在执行SQL查询之前触发的回调方法
afterQuery      | callback | 无                   | 在执行SQL查询之后触发的回调方法

#### beforeQuery回调的参数

名称    | 类型      | 说明
--------|-----------|------
$sql    | string    | 当前执行的SQL语句
$params | array     | 执行语句的参数
$types  | array     | 执行语句的参数类型
$db     | Widget\Db | 当前Db微件对象

#### afterQuery回调的参数

名称    | 类型      | 说明
--------|-----------|------
$db     | Widget\Db | 当前Db微件对象

### DSN配置

数据库     | 参考格式                                                  | 完整配置链接
-----------|-----------------------------------------------------------|--------------
MySQL      | mysql:host=localhost;port=3306;dbname=testdb;charset=utf8 | http://www.php.net/manual/en/ref.pdo-mysql.connection.php
SQLite     | sqlite:/opt/databases/mydb.sq3                            | http://www.php.net/manual/en/ref.pdo-sqlite.connection.php
PostgreSQL | pgsql:host=localhost;port=5432;dbname=testdb              | http://www.php.net/manual/zh/ref.pdo-pgsql.connection.php

### 方法

#### db($table)
根据数据表名称,创建一个新Query Builder对象

**返回:** `Widget\Db\QueryBuilder`

**参数**

名称      | 类型   | 说明
----------|--------|------
$table    | string | 数据表的名称,如`user`,或带别名形式的`user u`

#### db->insert($table, $data = array())
向指定的数据表插入一条数据

**返回:** `int` 受影响的行数

**参数**

名称   | 类型   | 说明
-------|--------|------
$table | string | 要插入的数据表名称
$data  | array  | 要插入的数据,数组的键名是数据表的字段名称,值是字段的值

#### db->lastInsertId($sequence = null)
获取最后插入数据表的自增编号

**返回:** `string`

**参数**

名称      | 类型   | 说明
----------|--------|------
$sequence | string | 序列名称,数据库驱动为`PostgreSQL`时才需要提供该参数

#### db->update($table, $data = array(), $conditions = array())
根据条件更新数据表数据

**返回:** `int` 受影响的行数

**参数**

名称        | 类型   | 说明
------------|--------|------
$table      | string | 要更新的数据表名称
$data       | array  | 要更新的数据
$conditions | array  | 更新语句的查询条件

#### db->delete($table, $conditions = array())
根据条件删除数据表数据

**返回:** `int` 受影响的行数

**参数**

名称        | 类型   | 说明
------------|--------|------
$table      | string | 要删除的数据表名称
$conditions | array  | 删除语句的查询条件

#### db->select($table, $conditions = array())
根据条件查找数据表的一条记录

**返回:** `array`|`false` 如果记录存在,返回记录数组,不存在返回`false`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$table      | string       | 要查找的数据表名称
$conditions | string,array | 查询条件,如果类型是字符串,表示主键的值,如果是数组,键名表示数据表字段,值表示字段的值

#### db->selectAll($table, $conditions = array())
根据条件查找数据表的所有匹配记录

**返回:** `array`|`false` 如果记录存在,返回二维记录数组,不存在返回`false`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$table      | string       | 要查找的数据表名称
$conditions | string,array | 查询条件,如果类型是字符串,表示主键的值,如果是数组,键名表示数据表字段,值表示字段的值

#### db->fetch($sql, $params = array())
执行一条SQL语句并返回第一条记录,主要用于SELECT的SQL语句

**返回:** `array`|`false` 如果记录存在,返回记录数组,不存在返回`false`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$sql        | string       | 要执行的SQL语句
$params     | array        | 绑定到SQL的参数

#### db->fetchAll($sql, $params = array())
执行一条SQL语句并返回所有记录

**返回:** `array`|`false` 如果记录存在,返回二维记录数组,不存在返回`false`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$sql        | string       | 要执行的SQL语句
$params     | array        | 绑定到SQL的参数

#### db->fetchColumn($sql, $params = array(), $column = 0)
执行一条SQL语句,并返回指定项目的值

**返回:** `string`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$sql        | string       | 要执行的SQL语句
$params     | array        | 绑定到SQL的参数
$column     | int          | 返回第几项的值

#### db->find($table, $conditions)
根据条件查找数据表的一条记录

**返回:** `Widget\Db\Record`|`false` 如果记录存在,返回记录对象,否则返回`false`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$table      | string       | 要查找的数据表名称
$conditions | string,array | 查询条件,如果类型是字符串,表示主键的值,如果是数组,键名表示数据表字段,值表示字段的值

#### db->findAll($table, $conditions)
根据条件查找数据表的所有匹配记录

**返回:** `Widget\Db\Collection`|`false` 如果记录存在,返回集合对象,否则返回`false`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$table      | string       | 要查找的数据表名称
$conditions | string,array | 查询条件,如果类型是字符串,表示主键的值,如果是数组,键名表示数据表字段,值表示字段的值

#### db->create($table, $data = array())
创建一个新的数据表记录对象

**返回:** `Widget\Db\Record` 记录对象

**参数**

名称        | 类型         | 说明
------------|--------------|------
$table      | string       | 记录的数据表名称
$data       | array        | 初始化的数据

#### db->findOrCreate($table, $id, $data = array())
根据条件查找数据表的一条记录,如果记录不存在,将根据`$data`创建一条新的数据表记录对象

**返回:** `Widget\Db\Record` 记录对象

**参数**

名称        | 类型         | 说明
------------|--------------|------
$table      | string       | 记录的数据表名称
$id         | string       | 主键的值
$data       | array        | 初始化的数据

#### db->executeUpdate($sql, $params = array())
执行一条SQL语句,并返回影响的行数,主要用于INSERT/UPDATE/DELETE操作的SQL语句

**返回:** `int` 受影响的行数

**参数**

名称        | 类型         | 说明
------------|--------------|------
$sql        | string       | 要执行的SQL语句
$params     | array        | 绑定到SQL的参数

#### db->query($sql, $params = array())
执行一条SQL语句,并返回`PDOStatement`对象

**返回:** `PDOStatement`

名称        | 类型         | 说明
------------|--------------|------
$sql        | string       | 要执行的SQL语句
$params     | array        | 绑定到SQL的参数

#### db->errorCode()
获取PDO错误代号

**返回:** `int`

#### db->errorInfo()
获取PD错误信息数组

**返回:** `array`

#### db->isConnected()
检查是否已经连接到数据库

**返回:** `bool`

#### db->getRecordClass($table)
根据数据表名称获取记录类名称

**返回:** `string` 如果记录类不存在,返回默认类`Widget\Db\Record`

### 魔术方法

#### db->$table
创建一个新的数据表记录对象,等于`db->create($table, array())`

**返回:** `Widget\Db\Record`

#### db->$table($conditions)
根据条件查找数据表的一行记录,返回的是一个`Widget\Db\Record`对象,等于`db->find($table, $conditions)`

**返回:** `Widget\Db\Record`