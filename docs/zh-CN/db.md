Db
==

数据库操作

目前支持`mysql`, `sqlite` 和 `pgsql`.

案例
----

### (CRUD)增删查改操作
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

调用方式
--------

### 选项



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

#### db->executeUpdate($sql, $params = array())
执行一条SQL语句,并返回影响的行数,主要用于INSERT/UPDATE/DELETE操作的SQL语句

#### db->query($sql, $params = array())
执行一条SQL语句,并返回`PDOStatement`对象

#### db->errorCode()
获取PDO错误代号

#### db->errorInfo()
获取PD错误信息数组