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

#### 通过`beforeCreate`回调,使用UUID作为主键的值

```php
class User extends \Wei\Record
{
    public function beforeCreate()
    {
        if (!$this['id']) {
            // 调用uuid服务生成一个新的uuid
            $this['id'] = wei()->uuid();
        }
    }
}
```

#### 通过`beforeSave`和`afterSave`使数据表字段支持数组格式数据

```php
class Article extends \Wei\Record
{
    public function beforeSave()
    {
        // 将数组转换为字符串以便保存到数据库
        $this['images'] = json_encode($this['images']);
    }
    
    public function afterSave()
    {
        // 转换回数组以便后续使用
        $this['images'] = json_decode($this['images'], true);
    }
}

$article = wei()->db('article');

$article['title'] = 'This is title';
$article['content'] = 'This is content';
$article['images'] = array('...', '...', '...');

$article->save();
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

### 回调方法

#### afterLoad($record, $wei)
加载记录后触发的回调

#### beforeSave($record, $wei)
保存记录前触发的回调

#### afterSave($record, $wei)
保存记录后触发的回调

#### beforeCreate($record, $wei)
插入记录到数据库前触发的回调

#### afterCreate($record, $wei)
插入记录到数据库后触发的回调

#### beforeUpdate($record, $wei)
更新记录到数据库前触发的回调

#### afterUpdate($record, $wei)
更新记录到数据库后触发的回调

#### beforeDestroy($record, $wei)
删除记录前触发的回调

#### afterDestroy($record, $wei)
删除记录后触发的回调