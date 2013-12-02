Active Record Query Builder
===========================

Query Builder是一个简单的SQL查询构建器.

案例
----

###  从用户表里查询id为`1`且用户名为`twin`的用户

```php
$member = wei()->db('member')
    ->select('id, username')
    ->where('id = 1')
    ->andWhere('username = ?', 'twin')
    ->orderBy('id', 'DESC')
    ->find();

// 执行的SQL语句如下
// SELECT id, title FROM member WHERE id = 1 AND author = ? ORDER BY id DESC LIMIT 1
```

### 创建一个新的QueryBuilder

```php
$qb = wei()->db('member');
```

### 字符串查询条件

```php
$qb = wei()->db('member')->where("name = 'twin'");

// 执行SQL: SELECT * FROM member WHERE name = 'twin' LIMIT 1
$member = $qb->find();
```

### 通过问号占位符`?`构造查询条件

```php
$qb = wei()->db('member')->where('name = ?', 'twin');

// 执行SQL: SELECT * FROM member WHERE name = ? LIMIT 1
$member = $qb->find();
```

### 通过多个问号占位符`?`构造查询条件

```php
$qb = wei()->db('member')>where('group_id = ? AND name = ?', array('1', 'twin'));

// 执行SQL: SELECT * FROM member WHERE group_id = ? AND name = ?  LIMIT 1
$member = $qb->find();
```

### 通过命名占位符`:`构造查询条件

```php
$qb = wei()->db('member')
        ->where('group_id = :groupId AND name = :name', array(
            'groupId' => '1',
            'name' => 'twin'
        ));

// 执行SQL: SELECT * FROM member WHERE group_id = :groupId AND name = :name
$member = $qb->find();
```

> #### 说明
>
> 使用问号占位符`?`有助于提高开发效率,而使用命名占位符`:`有助于增加可读性.
>
> 在同一条SQL语句中只能使用一种占位符.
>
> 推荐单表SQL语句均使用问号占位符`?`,多表且较复杂语句才使用命名占位符`:`.

### 构造范围查询

```php
$qb = wei()->db('member')->where('group_id BETWEEN ? AND ?', array('1', '2'));

// 执行SQL: SELECT * FROM member WHERE group_id BETWEEN ? AND ?
$member = $qb->find();
```

### 构造IN查询

```php
$qb = wei()->db('member')
        ->where(array(
            'id' => '1',
            'group_id' => array('1', '2')
        ));

// 执行SQL: SELECT * FROM member WHERE id = ? AND group_id IN (?, ?) LIMIT 1
$member = $qb->find();
```

### 构造ORDER BY语句

```php
$qb = wei()->db('member')->orderBy('id', 'ASC');

$member = $qb->find();

// 执行SQL: SELECT * FROM member ORDER BY id ASC LIMIT 1
```

### 增加ORDER BY语句

```php
$qb = wei()->db('member')->orderBy('id', 'ASC')->addOrderBy('group_id', 'ASC');

$member = $query->find();

// 执行SQL: SELECT * FROM member ORDER BY id ASC, group_id ASC LIMIT 1
```

### 设置SELECT查询的字段

```php
$qb = wei()->db('member')->select('id, group_id');

$member = $query->find();

// 执行SQL: SELECT id, group_id FROM member LIMIT 1
```

### 增加SELECT查询的字段

```php
$qb = wei()->db('member')->select('id')->addSelect('group_id');

$member = $query->find();

// 执行SQL: SELECT id, group_id FROM member LIMIT 1
```

### 构造LIMIT和OFFSET语句

```php
$qb = wei()->db('member')->limit(2);

// 生成SQL: SELECT * FROM member LIMIT 2
echo $qb->getSql();

$qb = wei()->db('member')->limit(1)->offset(1);

// 生成SQL: SELECT * FROM member LIMIT 1 OFFSET 1
echo $qb->getSql();

$qb = wei()->db('member')->limit(3)->page(3);

// 生成SQL: SELECT * FROM member LIMIT 3 OFFSET 6
echo $qb->getSql();
```

> #### 注意
>
> LIMIT的最小值为1,OFFSET的最小值为0,如果提供了无法解析的数值,将被转换为最小值.

### 构造GROUP BY语句

```php
$qb = wei()->db('member')->groupBy('id, group_id');

// 生成SQL: SELECT * FROM member GROUP BY id, group_id
echo $qb->groupBy();
```

### 构造HAVING语句

```php
$qb = wei()->db('member')->groupBy('id, group_id')->having('group_id >= ?', '1');

// 生成SQL: SELECT * FROM member GROUP BY id, group_id HAVING group_id >= ?
echo $qb->getSql();
```

### 构造JOIN语句

```php
$qb = wei()->db('member')
        ->select('member.*, member_group.name AS group_name')
        ->leftJoin('member_group', 'member_group.id = member.group_id');

// 生成SQL: SELECT member.*, member_group.name AS group_name FROM member LEFT JOIN member_group ON member_group.id = member.group_id
echo $qb->getSql();
```

### 重置已有的查询条件

```php
$qb = wei()->db('member')->where('id = 1')->orderBy('id', 'DESC');

// 生成SQL: SELECT * FROM member WHERE id = 1 ORDER BY id DESC
echo $qb->getSql();

$qb->reset('where');

// 生成SQL: SELECT * FROM member ORDER BY id DESC
echo $qb->getSql();
```

### 使用`indexBy`控制返回二维数组的键名

```php
$qb = wei()->db('member')->indexBy('name')->limit(2);

$data = $qb->fetchAll();

// 使用前,数组的键名是数字,从0开始
$data = array(
    0 => array(
        'id' => '1',
        'name' => 'twin',
    ),
    1 => array(
        'id' => '2',
        'name' => 'test'
    )
);

// 使用后,数组的键名被替换为指定字段的值
$data = array(
    'twin' => array(
        'id' => '1',
        'name' => 'twin',
    ),
    'test' => array(
        'id' => '2',
        'name' => 'test'
    )
);
```

> #### 注意
>
> 1. `indexBy`仅对`fetchAll`和`findAll`的返回值有作用,`execute`方法仍然返回原始的数组
> 2. 如果字段的值重复,后面的值将覆盖前面的值

调用方式
--------

### 方法

#### db($table)
根据数据表名称,创建一个新Query Builder对象

**返回:** `Wei\Record`

#### $qb->select($select = null)
设置SELECT字句要查询的字段名称

**返回:** `Wei\Record`

#### $qb->addSelect($select = null)
增加SELECT子句要查询的字段名称

**返回:** `Wei\Record`

#### $qb->delete($table = null)
设置SQL语句为DELETE操作

**返回:** `Wei\Record`

#### $qb->update($table = null)
设置SQL语句为UPDATE操作

**返回:** `Wei\Record`

#### $qb->from($table)
设置`FROM`字句的数据表名称

**返回:** `Wei\Record`

#### $qb->where($conditions, $params = null, $types = array())
设置`WHERE`查询条件

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$conditions | string       | 查询条件,如`id = 1`, `id = ?`
$params     | array        | 参数的值
$types      | array        | 参数的类型

#### $qb->andWhere($conditions, $params = null, $types = array())
增加`AND`类型的`WHERE`条件到当前查询中

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$conditions | string       | 查询条件,如`id = 1`, `id = ?`
$params     | array        | 参数的值
$types      | array        | 参数的类型

#### $qb->orWhere($conditions, $params = null, $types = array())
增加`OR`类型的`WHERE`条件到当前查询中

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$conditions | string       | 查询条件,如`id = 1`, `id = ?`
$params     | array        | 参数的值
$types      | array        | 参数的类型

#### $qb->orderBy($sort, $order = 'ASC')
设置`ORDER BY`字句

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$sort       | string       | 排序的字段名称
$order      | string       | 排序类型,`ASC`或`DESC`

#### $qb->addOrderBy($sort, $order = 'ASC')
添加`ORDER BY`字句到当前查询中

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$sort       | string       | 排序的字段名称
$order      | string       | 排序类型,`ASC`或`DESC`

#### $qb->offset($offset)
设置`OFFSET`字句

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$offset     | int          | `OFFSET`字句的值,如0,10

#### $qb->limit($limit)
设置`LIMIT`字句

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$limit      | int          | `LIMIT`字句的值,如0,10

#### $qb->page($page)
设置`OFFSET`和`LIMIT`字句

通过该方法设置`$page`的值之后,`OFFSET`的值等于`($page - 1) * $limit`,如果`$limit`为空,`$limit`会被设置为`10`

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$page       | int          | 当前在第几页

#### $qb->join($table, $on = null)
增加`INNER JOIN`字句到当前查询中

**返回:** `Wei\Record`

**参数**

名称   | 类型         | 说明
-------|--------------|------
$table | string       | 要连接的数据表名称,如`userGroup`
$on    | string       | 连接的关联条件,如`user.groupId = userGroup.id`

#### $qb->innerJoin($table, $on = null)
同上,增加`INNER JOIN`字句到当前查询中

#### $qb->leftJoin($table, $on = null)
增加`LEFT JOIN`字句到当前查询中

**返回:** `Wei\Record`

**参数**

名称   | 类型         | 说明
-------|--------------|------
$table | string       | 要连接的数据表名称,如`userGroup`
$on    | string       | 连接的关联条件,如`user.groupId = userGroup.id`

#### $qb->rightJoin($table, $on = null)
增加`RIGHT JOIN`字句到当前查询中

**返回:** `Wei\Record`

**参数**

名称   | 类型         | 说明
-------|--------------|------
$table | string       | 要连接的数据表名称,如`userGroup`
$on    | string       | 连接的关联条件,如`user.groupId = userGroup.id`

#### $qb->groupBy($groupBy)
设置`GROUP BY`字句

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$groupBy    | string       | `GROUP BY`字句的值

#### $qb->addGroupBy($groupBy)
添加`GROUP BY`字句到当前查询中

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$groupBy    | string       | `GROUP BY`字句的值

#### $qb->having($conditions, $params = array(), $types = array())
设置`HAVING`字句

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$conditions | string       | 查询条件,如`id = 1`, `id = ?`
$params     | array        | 参数的值
$types      | array        | 参数的类型

#### $qb->andHaving($conditions, $params = array(), $types = array())
添加`AND`类型`HAVING`字句到当前查询中

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$conditions | string       | 查询条件,如`id = 1`, `id = ?`
$params     | array        | 参数的值
$types      | array        | 参数的类型

#### $qb->orHaving($conditions, $params = array(), $types = array())
添加`OR`类型的`HAVING`字句到当前查询中

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$conditions | string       | 查询条件,如`id = 1`, `id = ?`
$params     | array        | 参数的值
$types      | array        | 参数的类型

#### $qb->indexBy($column)
控制返回二维数组的键名为指定字段的值

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$column     | string       | 字段的名称,必须存在select语句中

#### $qb->reset($queryPartName)
重置某一部分SQL字句

**返回:** `Wei\Record`

**参数**

名称           | 类型         | 说明
---------------|--------------|------
$queryPartName | string       | 允许的值为`select`,`from`,`join`,`set`,`where`,`groupBy`,`having`,`orderBy`,`limit`或`offset`

#### $qb->resetAll($queryPartNames = null)
重置某一部分或全部SQL字句

**返回:** `Wei\Record`

**参数**

名称            | 类型         | 说明
----------------|--------------|------
$queryPartNames | null|array   | 留空表示重置所有字句,作为数组时,允许的值为`select`,`from`,`join`,`set`,`where`,`groupBy`,`having`,`orderBy`,`limit`或`offset`

#### $qb->find()
执行构造的SQL语句,并返回一个`Wei\Record`对象,如果结果为空,返回`false`

**返回:** `Wei\Record`|`false`

#### $qb->findAll()
执行构造的SQL语句,并返回一个`Wei\Record`对象

**返回:** `Wei\Record`

#### $qb->fetch()
执行构造的SQL语句,并返回一个一维数组,如果结果为空,返回`false`

**返回:** `array`|`false`

#### $qb->fetchAll()
执行构造的SQL语句,并返回一个二维数组,如果结果为空,返回`false`

**返回:** `array`|`false`

#### $qb->count()
获取当前构造的SQL语句所查找到的行数

**返回:** `int`

#### $qb->setParameter($key, $value, $type = null)
设置绑定参数的值和类型

**返回:** `Wei\Record`

**参数**

名称        | 类型         | 说明
------------|--------------|------
$key        | string       | 参数的名称,对于使用命名占位符的预处理语句，应是类似 :name 形式的参数名。对于使用问号占位符的预处理语句，应是以1开始索引的参数位置。
$value      | mixed        | 参数的值
$type       | int          | 参数的类型,使用 PDO::PARAM_* 常量明确地指定参数的类型

相关链接: http://php.net/manual/zh/pdostatement.bindvalue.php

#### $qb->setParameters(array $params, array $types = array())
设置多个绑定参数的值和类型

**返回:** `Wei\Record`

#### $qb->getParameter($key)
获取绑定参数的值

**返回:** `mixed`

#### $qb->getParameters()
获取所有绑定参数的值

**返回:** `array`

#### $qb->getTable()
获取当前查询的数据表名称

**返回:** `string`

#### $qb->getSql()
获取当前查询的完整SQL语句

**返回:** `string`
