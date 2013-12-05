Active Record Status
====================

在Record对象数据更新,保存时,内部状态会跟随改变.

案例
----

    TODO

调用方式
--------

### 方法

#### $record->isNew()
判断当前记录是否为新建,并且未保存到数据库

#### $record->isChanged($field = null)
判断当前记录,或指定的字段值是否修改过

#### $record->getChangedData($field = null)
获取当前记录所有,或指定字段被修改过的值

#### $record->isDestroyed()
判断当前记录是否已被删除
