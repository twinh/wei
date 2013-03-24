[isDir()](http://twinh.github.com/widget/api/isDir)
===================================================

检查数据是否为存在的目录

### 
```php
bool isDir( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notFound`            | %name%必须是存在的目录                                         |
| `negative`            | %name%必须是不存在的目录                                       |
| `notString`           | %name%必须是字符串                                             |


##### 代码范例
检查"/notfound/directory"是否为存在的目录
```php
<?php

if ($widget->isDir('/notfound/directory')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
