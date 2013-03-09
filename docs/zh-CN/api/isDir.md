[isDir()](http://twinh.github.com/widget/api/isDir)
===================================================

检查数据是否为存在的目录

### 
```php
bool isDir( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查"/notfound/directory"是否为存在的目录
```php
<?php

if ($widget->isDir('/notfound/directory')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'No'
```
