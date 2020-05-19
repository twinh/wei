Uuid
====

生成一个随机的UUID

案例
----

### 随机生成一个UUID
```php
// 输出格式类似"05b59eca-866d-410b-96d7-e4f2c286f5a8"
echo wei()->uuid();
```

调用方式
--------

### 选项

*无*

### 方法

#### uuid()
生成一个随机的UUID

生成的UUID可用于数据表主键,相比通过SQL语句`SELECT UUID`生成的UUID要快很多,而且调用更加方便.
