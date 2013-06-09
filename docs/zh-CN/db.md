Db
==

数据库操作

目前支持`mysql`, `sqlite` 和 `pgsql`.

案例
----

### 增删查改(CRUD)操作
```php
// 插入数据
$widget->db->insert('user', array(
    'username' => 'twin',
    'createdAt' => date('Y-m-d H:i:s') 
));

// 更新数据
$widget->db->update(
    'user', 
    array('username' => 'twin'), 
    array('id' => '1')
);

// 删除数据
$widget->db->delete('user', array('id' => '1'));

// 查找id为1的用户
$widget->db->select('user', 1);

// 查找所有分组编号为1的用户
$widget->db->selectAll('user', array('groupId' => '1'));
```

### Active Record模式

Active Record模式是将数据表的每一行映射为一个对象,数据表的字段与对象的属性一一对应.
完整的介绍请查看维基百科的说明[Active Record](http://zh.wikipedia.org/wiki/Active_Record)

#### 创建对象并保存
```php
// 创建一个新的用户记录对象
/* @var $user \Widget\Db\Record */
$user = $widget->db->user;

// 或是通过`create`方法创建
$user = $widegt->db->create('user', array('groupId' => 1));

// 设置对象的值
$user->username = 'twin';
$user->createdAt = date('Y-m-d H:i:s');

// 保存到数据库中
$user->save();
```

#### 更新对象数据
```php
// 查找主键为1的用户
$user = $widget->db->find('user', '1');

// 或是通过魔术方法更自然的获取对象
$user = $widget->db->user(1);

// 更新对象的值
$user->username = 'twin';

// 保存到数据库中 
$user->save();
```

#### 删除对象
```php
// 查找主键为1的用户
$user = $widget->db->user(1);

// 删除该记录
$user->delete();
```

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

### DSN配置

数据库     | 参考格式                                                  | 完整配置链接
-----------|-----------------------------------------------------------|--------------
MySQL      | mysql:host=localhost;port=3306;dbname=testdb;charset=utf8 | http://www.php.net/manual/en/ref.pdo-mysql.connection.php
SQLite     | sqlite:/opt/databases/mydb.sq3                            | http://www.php.net/manual/en/ref.pdo-sqlite.connection.php
PostgreSQL | pgsql:host=localhost;port=5432;dbname=testdb              | http://www.php.net/manual/zh/ref.pdo-pgsql.connection.php

### 方法

#### db
获取数据库查询对象

#### db->insert($table, $$data = array())
向指定的数据表插入一条数据

#### db->lastInsertId()
获取最后插入数据表的编号

#### db->update($table, $data = array(), $identifier = array())
根据条件更新数据表数据

#### db->delete($table, $identifier = array())
根据条件删除数据表数据

#### db->select($table, $where)
根据条件查找数据表的一条记录

#### db->selectAll($table, $where = false)
根据条件查找数据表的所有匹配记录

#### db->fetch($sql, $params = array())
执行一条SQL语句并返回第一条记录,主要用于SELECT的SQL语句

#### db->fetchAll($sql, $params = array())
执行一条SQL语句并返回所有记录

#### db->fetchColumn($sql, $params = array(), $column = 0)
执行一条SQL语句,并返回指定类的值

#### db->find($table, $id)
根据条件查找数据表的一条记录,返回的是一个`Widget\Db\Record`对象

#### db->findAll($table, $where)
根据条件查找数据表的所有匹配记录,返回的是一个`Widget\Db\Collection`对象

#### db->create($table, $data)
创建一个新的数据表记录对象

#### db->findOrCreate($table, $id, $data = array())
根据条件查找数据表的一条记录,如果记录不存在,将根据`$data`创建一条新的数据表记录对象

#### db->executeUpdate($sql, $params = array())
执行一条SQL语句,并返回影响的行数,主要用于INSERT/UPDATE/DELETE操作的SQL语句

#### db->query($sql, $params = array())
执行一条SQL语句,并返回`PDOStatement`对象

#### db->errorCode()
获取PDO错误代号

#### db->errorInfo()
获取PD错误信息数组

#### db->getRecordClass($table)
根据数据表名称获取记录类名称

### 魔术方法

#### db->$table
创建一个新的数据表记录对象,等于`db->create($table, array())`

#### db->$table($where)
根据条件查找数据表的一条记录,返回的是一个`Widget\Db\Record`对象,等于`db->find($table, $where)`