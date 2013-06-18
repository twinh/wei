QueryBuilder
============

QueryBuilder是一个简单的SQL查询构建器.

案例
----

###  从文章表里查询id为1且用户名为twin的用户
```php
$member = widget()->db('member')
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
// 创建一个新的QueryBuilder
$qb = widget()->db->createQueryBuilder()

// 创建一个指定数据表的QueryBuilder
$qb = widget()->db('member');
```

### 字符串查询条件
```php
$qb = widget()->db('member')->where("name = 'twin'");

$member = $qb->find();

// 执行SQL: SELECT * FROM member WHERE name = 'twin' LIMIT 1
```

### 通过问号占位符(`?`)构造查询条件
```php
$qb = widget()->db('member')->where('name = ?', 'twin');

$member = $qb->find();

// 执行SQL: SELECT * FROM member WHERE name = ? LIMIT 1
```

### 通过多个问号占位符(`?`)构造查询条件
```php
$qb = widget()->db('member')>where('group_id = ? AND name = ?', array('1', 'twin'));

$member = $qb->find();

// 执行SQL: SELECT * FROM member WHERE group_id = ? AND name = ?  LIMIT 1
```

### 通过冒号占位符`:`构造查询条件
```php
$qb = widget()->db('member')
        ->where('group_id = :groupId AND name = :name', array(
            'groupId' => '1',
            'name' => 'twin'
        ));

$member = $qb->find();

// 执行SQL: SELECT * FROM member WHERE group_id = :groupId AND name = :name
```

### 构造范围查询
```php
$qb = widget()->db('member')->where('group_id BETWEEN ? AND ?', array('1', '2'));

$member = $qb->find();

// 执行SQL: SELECT * FROM member WHERE group_id BETWEEN ? AND ?
```

### 构造IN查询
```php
$qb = widget()->db('member')
        ->where(array(
            'id' => '1',
            'group_id' => array('1', '2')
        ));

$member = $qb->find();

// 执行SQL: SELECT * FROM member WHERE id = ? AND group_id IN (?, ?) LIMIT 1
```

### 构造ORDER BY语句
```php
$qb = widget()->db('member')->orderBy('id', 'ASC');

$member = $qb->find();

// 执行SQL: SELECT * FROM member ORDER BY id ASC LIMIT 1
```

### 增加ORDER BY语句
```php
$qb = widget()->db('member')->orderBy('id', 'ASC')->addOrderBy('group_id', 'ASC');

$member = $query->find();

// 执行SQL: SELECT * FROM member ORDER BY id ASC, group_id ASC LIMIT 1
```

### 设置SELECT查询的字段
```php
$qb = widget()->db('member')->select('id, group_id');

$member = $query->find();

// 执行SQL: SELECT id, group_id FROM member LIMIT 1
```

### 增加SELECT查询的字段
```php
$qb = widget()->db('member')->select('id')->addSelect('group_id');

$member = $query->find();

// 执行SQL: SELECT id, group_id FROM member LIMIT 1
```

### 设置LIMIT和OFFSET语句
```php
$qb = widget()->db('member')->limit(2);

// 生成SQL: SELECT * FROM member LIMIT 2
echo $qb->getSql();

$qb = widget()->db('member')->limit(1)->offset(1);

// 生成SQL: SELECT * FROM member LIMIT 1 OFFSET 1
echo $qb->getSql();

$qb = widget()->db('member')->limit(3)->page(3);

// 生成SQL: SELECT * FROM member LIMIT 3 OFFSET 6
echo $qb->getSql();
```

### 区分find和findAll

    TODO

调用方式
--------

### 方法
