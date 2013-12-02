Active Record Callbacks
=======================

在Record对象从创建,更新到销毁的生命周期中,会触发相应的回调,以便我们更灵活的控制对象数据.

案例
----

#### 使用`beforeSave`回调为对象自动加上创建和修改时间

```php
class User extends \Wei\Record
{
    public function beforeSave()
    {
        if ($this->isNew && !$this['createTime']) {
            $this['createTime'] = date('Y-m-d H:i:s');
        }
        $this['updateTime'] = date('Y-m-d H:i:s');
    }
}

// 创建一个新的记录对象并保存
$user = wei()->db('user');
$user['username'] = 'twin';
$user->save();

// 输出创建时间
echo $user['createTime'];

// 输出结果类似
'2013-12-12 14:30:00'
```

#### 通过`afterLoad`和`beforeSave`控制对象数据格式

```php

```

回调调用顺序
-----------

#### 创建新记录并保存

* afterLoad
* beforeSave
* beforeCreate
* afterCreate
* afterSave

#### 更新已有记录并保存

* afterLoad
* beforeSave
* beforeUpdate
* afterUpdate
* afterSave

#### 删除记录

* afterLoad
* beforeDestroy
* afterDestroy