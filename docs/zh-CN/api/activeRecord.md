Active Record
=============


案例
====

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

```php
// 删除id为1的用户
wei()->db('user')->delete(array('id' => 1));
```

### 回调调用顺序

#### 创建新记录并保存

* afterLoad
* beforeSave
* beforeInsert
* afterInsert
* afterSave

#### 更新已有记录并保存

* afterLoad
* beforeSave
* beforeUpdate
* afterUpdate
* afterSave

#### 删除记录

* afterLoad
* beforeDelete
* afterDelete

调用方式
--------

### 属性

#### Wei\Db $db
数据库连接对象

#### Wei\Wei $wei
服务管理器

### 回调

#### afterLoad($record, $wei)
加载记录后触发的回调

#### beforeSave($record, $wei)
保存记录前触发的回调

#### afterSave($record, $wei)
保存记录后触发的回调

#### beforeInsert($record, $wei)
插入记录到数据库前触发的回调

#### afterInsert($record, $wei)
插入记录到数据库后触发的回调

#### beforeUpdate($record, $wei)
更新记录到数据库前触发的回调

#### afterUpdate($record, $wei)
更新记录到数据库后触发的回调

#### beforeDelete($record, $wei)
删除记录前触发的回调

#### afterDelete($record, $wei)
删除记录后触发的回调

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

#### $record->delete()
从数据库中删除该记录数据

#### $record->remove($field)
删除指定字段的值

#### $record->isNew()
判断当前记录是否为新建,并且未保存到数据库

#### $record->isModified()
判断当前记录的字段值是否修改过