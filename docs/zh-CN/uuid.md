[uuid()](http://twinh.github.io/widget/api/uuid)
================================================

生成一个随机的UUID

### 
```php
bool uuid( )
```

##### 参数
*无*


生成的UUID可用于数据表主键,相比通过SQL语句`SELECT UUID`生成的UUID要快很多,而且调用更加方便.

值得注意的是,UUID是固定长度和格式的随机的字符串,所以下面的例子中,你输出的结果和我的结果肯定不一样.


##### 代码范例
随机生成一个UUID
```php
<?php

echo $widget->uuid();
```
##### 运行结果
```php
'05b59eca-866d-410b-96d7-e4f2c286f5a8'
```
