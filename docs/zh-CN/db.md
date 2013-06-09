Db
==

数据库操作

目前支持`mysql`, `sqlite` 和 `pgsql`.

案例
----

### (CRUD)增删查改操作
```php
// 插入数据
$widget->db->insert('user', array(
    'username' => 'twin',
    'createdAt' => date('Y-m-d H:i:s') 
));

// 更新数据
$widget->db->update(
    'user', 
    array('username' => 'twin'), 
    array('id' => '1')
);

// 删除数据
$widget->db->delete('user', array('id' => '1'));
```


调用方式
--------

### 选项

### 方法

#### db
获取数据库查询对象