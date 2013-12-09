Active Record 基本操作
======================

> Active Record是一种领域模型模式，特点是一个模型类对应关系型数据库中的一个表，而模型类的一个实例对应表中的一行记录。
>
> -- <cite>[维基百科 Active Record](http://zh.wikipedia.org/wiki/Active_Record)</cite>

案例
----

### 创建一条新记录并保存

```php
// 通过`db`方法创建一个新的用户对象
$user = wei()->db('user');

// 设置对象的值
$user['username'] = 'twin';
$user['createdAt'] = date('Y-m-d H:i:s');

// 保存到数据库中
$user->save();
```

### 查找并更新记录数据

```php
// 查找主键为1的用户,如果存在返回用户对象,不存在返回`false`
$user = wei()->db('user')->find(array('id' => 1));

// 更新对象的值
$user['username'] = 'twin';

// 保存到数据库中
$user->save();
```

### 删除记录

删除主键为1的用户

```php
wei()->db('user')->destroy(1);
```

删除username为"twin"的用户

```php
wei()->db('user')->destroy(array('username' => 'twin'));
```

### 实现软删除

```php
class User extends \Wei\Record
{
    public function softDelete()
    {
        $this['delete'] = true;
        $this['deleteTime'] = date('Y-m-d H:i:s');
        return $this->save();
    }
}

$user = wei()->db('user')->find(1);

$user->softDelete();
```

### Active Record与常规数据库查询的最大区别

在我个人看来,两者的最大区别在于返回的数据结构.

1. 常规查询将数据存储在`数组`中.
2. Active Record将数据存储在`数组对象(Array Object)`中,除了拥有数组所有特性外,在`数据层分离`,`缓存`,`延迟加载(Lazy load)`等都有很大的想象空间.

```php
/* @var $data array */
$data = wei()->db->fetch("SELECT * FROM table WHERE field = 'value'");
```

```php
/* @var $record \Wei\Record */
$record = wei()->db('table')->find(array('field' => 'value'));
```

调用方式
--------

### 属性

#### Wei\Db $db
数据库连接对象

#### Wei\Wei $wei
服务管理器

### 方法

#### $record[$field]
获取字段的值

#### $record[$field] = 'xxx'
设置字段的值

#### $record->get($field)
获取字段的值

#### $record->set($field, $value)
设置字段的值

#### $record->getTable()
获取数据表名称

#### $record->setTable($table)
设置数据表名称

#### $record->setPrimaryKey($primaryKey)
设置主键字段的名称

#### $record->getPrimaryKey()
获取主键字段的名称

#### $record->toArray()
以数组形式返回记录里的数据

#### $record->toJson()
以JSON字符串形式返回记录里的数据

#### $record->fromArray($data)
批量设置记录数据

#### $record->save()
保存记录数据到数据库中

#### $record->destroy()
从数据库中删除该记录数据

#### $record->remove($field)
删除指定字段的值

#### $record->isNew()
判断当前记录是否为新建,并且未保存到数据库

#### $record->isChanged()
判断当前记录的字段值是否修改过

#### $record->getChangedData($field = null)
获取指定字段或所有字段修改前的值