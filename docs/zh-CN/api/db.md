Db
==

基于PDO的数据库操作对象,支持基本的增删查改(CRUD)和流行的Active Record模式的数据库操作.

目前主要支持`MySQL`,`SQLite`和`PostgreSQL`数据库.

案例
----

### 增删查改(CRUD)操作

```php
// 插入数据
wei()->db->insert('user', array(
    'username' => 'twin',
    'createdAt' => date('Y-m-d H:i:s')
));

// 更新数据
wei()->db->update(
    'user',
    array('username' => 'twin'),
    array('id' => '1')
);

// 删除数据
wei()->db->delete('user', array('id' => '1'));

// 查找id为1的用户
wei()->db->select('user', 1);

// 查找所有分组编号为1的用户
wei()->db->selectAll('user', array('groupId' => '1'));
```

### Active Record模式

Active Record模式是将数据表的每一行映射为一个对象,数据表的字段与对象的属性一一对应.

完整的介绍请查看维基百科的说明[Active Record](http://zh.wikipedia.org/wiki/Active_Record)

[查看Active Record](activeRecord.md)

**创建记录并保存**

```php
// 创建一个新的用户记录对象
$user = wei()->db->create('user', array('groupId' => 1));

// 设置对象的值
$user->username = 'twin';
$user->createdAt = date('Y-m-d H:i:s');

// 保存到数据库中
$user->save();
```

**查找并更新记录数据**

```php
// 查找主键为1的用户
$user = wei()->db->find('user', '1');

// 或是通过魔术方法更自然地获取对象
$user = wei()->db->user(1);

// 更新对象的值
$user->username = 'twin';

// 保存到数据库中
$user->save();
```

**删除记录**

```php
// 查找主键为1的用户
$user = wei()->db->user(1);

// 删除该记录
$user->delete();
```

### SQL查询构建器

如果增删查改(CRUD)操作和Active Record模式还不能满足您的需求,你可以尝试使用QueryBuilder来生成更复杂的SQL语句

[查看QueryBuilder](queryBuilder.md)

### 连接到多个数据库

```php
$widget = wei(array(
    // 数据库对象的配置
    'db' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'dbname'    => 'widget',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => '123456',
    ),
    // 备机数据库对象的配置
    'slave.db' => array(
        'driver'    => 'mysql',
        'host'      => 'slave-host',
        'dbname'    => 'widget',
        'charset'   => 'utf-8',
        'user'      => 'root',
        'password'  => '123456',
    ),
    // 日志数据库对象的配置
    'logger.db' => array(
        'driver'    => 'mysql',
        'host'      => 'logger-host',
        'dbname'    => 'widget',
        'charset'   => 'utf-8',
        'user'      => 'root',
        'password'  => '123456',
    )
));

// 获取默认数据库对象
$db = $widget->db;

// 获取备机数据库对象
$slaveDb = $widget->slaveDb;

// 获取日志数据库对象
$loggerDb = $widget->loggerDb;

// 使用日志数据库对象查询用户编号为1的操作日志
$loggerDb->findAll('userLog', array('userId' => 1));
```

### 区分`fetch`,`select`和`find`方法

在db对象,`fetch`,`select`和`find`方法都是用于查询数据库数据,他们共同的特征是只返回第一行数据,不同点在于:

* `fetch`方法的第一个参数是完整的SQL语句,返回的是数组
* `select`方法的第一个参数是数据表名称,返回的是数组
* `find`方法的第一个参数也是数据表名称,但返回的是`Widget\Db\Record`对象

另外,如果要查询多条数据,对应的方法是`fetchAll`,`selectAll`和`findAll`.

```php
$db = wei()->db;

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

### 通过beforeQuery回调记录SQL日志

```php
$widget = wei(array(
    'db' => array(
        'beforeQuery' => function($sql, $params, $types, $db) {
            $log = $sql . "\n" . var_export($params, true);
            // 通过db对象获取logger对象,并调用debug方法记录SQL日志
            wei()->logger->debug($log);
        }
    )
));

$widget->db->query("SELECT DATE('now')");

// 日志文件内容
//[2013-07-10 23:15:38] widget.DEBUG: SELECT DATE('now')
//array (
//)
```

### 配置读写分离(master-slave)的数据库操作

通过`slaveDb`选项可配置备数据库,主要具有以下功能

* 读操作使用slave数据库
* 写操作使用master数据库

```php
$widget = wei(array(
    // 主数据库对象的配置
    'db' => array(
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'dbname'    => 'widget',
        'charset'   => 'utf8',
        'user'      => 'root',
        'password'  => '123456',
        // 重要: 通过`slaveDb`选项指定备机数据库的配置名称
        'slaveDb'   => 'slave.db'
    ),
    // 备机数据库对象的配置
    'slave.db' => array(
        'driver'    => 'mysql',
        'host'      => 'slave-host',
        'dbname'    => 'widget',
        'charset'   => 'utf-8',
        'user'      => 'root',
        'password'  => '123456',
    ),
));

// 获取主数据库对象
$db = $widget->db;

// 执行写操作,使用主数据库
$db->insert('table', array('key' => 'value'));
$db->update('table', array('set' => 'value'), array('where' => 'value'))

// 执行读操作,使用备数据库
$db->select('table', array('key' => 'value'));
$db->find('table', array('key' => 'value'));

// 获取备数据库对象
$slaveDb = $widget->slaveDb;

// 直接使用备数据库操作
$slaveDb->select('table', array('key' => 'value'));
```

调用方式
--------

### 选项

名称            |  类型    | 默认值               | 说明
----------------|----------|----------------------|------
dirver          | string   | mysql                | 数据库驱动类型,可以是`mysql`,`sqlite`或`pgsql`
user            | string   | 无                   | 连接数据库的用户名
password        | string   | 无                   | 连接数据库的密码
host            | string   | 127.0.0.1            | 数据库所在的主机地址,仅驱动为mysql和pgsql时有效
port            | string   | 无                   | 数据库服务器运行的端口,仅驱动为mysql和pgsql时有效
dbname          | string   | 无                   | 数据库名称,仅驱动为mysql和pgsql时有效
unixSocket      | string   | 无                   | MySQL数据库的Unix socket连接文件,仅驱动为mysql时有效
charset         | string   | 无                   | 连接数据库的字符集,仅驱动为mysql时有效
path            | string   | 无                   | SQLite数据库所在的路径,如果存储在内存中,使用`:memory:`
attrs           | array    | array()              | PDO的属性配置
recordClass     | string   | Widget\Db\Record     | 记录类的基础类名称
collectionClass | string   | Widget\Db\Collection | 记录集合类的基础类名称
recordClasses   | array    | array()              | 自定义记录类的数组,键名为数据表名称,值为记录类名称
recordNamespace | string   | 无                   | 自定义记录类的命名空间
slaveDb         | string   | 无                   | Slave数据库(用于读查询)的配置名称
global          | bool     | false                | 新创建的db服务是否使用默认`db`的选项
beforeConnect   | callback | 无                   | 在连接PDO之前触发的回调方法
connectFails    | callback | 无                   | 连接PDO失败时触发的回调方法
afterConnect    | callback | 无                   | 连接PDO完成(成功)时触发的回调方法
beforeQuery     | callback | 无                   | 在执行SQL语句之前触发的回调方法
afterQuery      | callback | 无                   | 在执行SQL语句之后触发的回调方法

#### 不同驱动连接到数据库的连接选项

驱动类型 | 选项
---------|------
mysql    | user, password, host, port, dbname, unixSocket, charset
pgsql    | user, password, host, port, dbname
sqlite   | path

### 回调

#### beforeConnect

在连接PDO之前触发的回调方法

名称    | 类型      | 说明
--------|-----------|------
$db     | Widget\Db | 当前Db对象

#### connectFails

连接PDO失败时触发的回调方法

名称    | 类型         | 说明
--------|--------------|------
$db     | Widget\Db    | 当前Db对象
$e      | PDOException | PDO异常对象

#### afterConnect

连接PDO完成(成功)时触发的回调方法

名称    | 类型         | 说明
--------|--------------|------
$db     | Widget\Db    | 当前Db对象
$pdo    | PDO          | PDO对象

#### beforeQuery

在执行SQL语句之前触发的回调方法

名称    | 类型      | 说明
--------|-----------|------
$sql    | string    | 当前执行的SQL语句
$params | array     | 执行语句的参数
$types  | array     | 执行语句的参数类型
$db     | Widget\Db | 当前Db对象

#### afterQuery

在执行SQL语句之后触发的回调方法

名称    | 类型      | 说明
--------|-----------|------
$db     | Widget\Db | 当前Db对象

### 方法

#### db($table = null)
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

#### db->insertBatch($table, $data = array())
向指定的数据表插入多条数据(目前不支持`SQLite`)

**返回:** `int` 受影响的行数

**参数**

名称   | 类型   | 说明
-------|--------|------
$table | string | 要插入的数据表名称
$data  | array  | 要插入的二维数组数据

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

#### db->getUser()
获取连接数据库的用户名称

**返回:** `string`

#### db->getPassword()
获取连接数据库的用户密码

**返回:** `string`

#### db->getHost()
获取数据库所在的主机地址

**返回:** `string`

#### db->getPort()
获取数据库服务器运行的端口

**返回:** `string`

#### db->getDbname()
获取数据库的名称

**返回:** `string`

#### db->getDsn()
获取连接到PDO的DSN字符串

**返回:** `string`

#### db->getLastSql()
获取最后执行的SQL语句

**返回:** `string`

### 魔术方法

#### db->$table($conditions)
根据条件查找数据表的一行记录,返回的是一个`Widget\Db\Record`对象,等于`db->find($table, $conditions)`

**返回:** `Widget\Db\Record`
