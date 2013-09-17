Active Record
=============


案例
====

### 创建一条新记录并保存

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

### 查找并更新记录数据

```php
// 查找主键为1的用户
$user = widget()->db->find('user', '1');

// 或是通过魔术方法更自然地获取对象
$user = widget()->db->user(1);

// 更新对象的值
$user->username = 'twin';

// 保存到数据库中 
$user->save();
```

### 删除记录

```php
// 查找主键为1的用户
$user = widget()->db->user(1);

// 删除该记录
$user->delete();
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

#### Widget\Db $db
数据库连接对象

#### Widget\Widget $widget
微件管理对象

### 回调

#### afterLoad($record, $widget)
加载记录后触发的回调

#### beforeSave($record, $widget)
保存记录前触发的回调

#### afterSave($record, $widget)
保存记录后触发的回调

#### beforeInsert($record, $widget)
插入记录到数据库前触发的回调

#### afterInsert($record, $widget)
插入记录到数据库后触发的回调

#### beforeUpdate($record, $widget)
更新记录到数据库前触发的回调

#### afterUpdate($record, $widget)
更新记录到数据库后触发的回调

#### beforeDelete($record, $widget)
删除记录前触发的回调

#### afterDelete($record, $widget)
删除记录后触发的回调

### 方法

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

#### $record->setData($data)
批量设置记录数据,同`fromArray`

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

### 动态属性和方法

#### $record->$field
获取字段的值

#### $record->$field = 'xxx'
设置字段的值

#### unset($record->$field)
删除字段的值

### 数组操作方式

#### $record[$field]
获取字段的值

#### $record[$field] = 'xxx'
设置字段的值