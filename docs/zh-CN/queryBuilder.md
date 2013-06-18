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

### 字符串查询
```php
$qb = widget()->db('member')->where("name = 'twin'");

echo $qb->getSql();

// 输出: SELECT * FROM member WHERE name = 'twin' LIMIT 1
```