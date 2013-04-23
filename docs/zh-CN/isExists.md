[isExists()](http://twinh.github.io/widget/api/isExists)
========================================================

检查数据是否为存在的文件或目录

### 
```php
bool isExists( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `notFound`            | %name%必须是存在的文件或目录                                   |
| `negative`            | %name%必须是不存在的文件或目录                                 |
| `notString`           | %name%必须是字符串                                             |

##### 代码范例
检查路径"/notfound/directory"是否存在
```php
<?php

if ($widget->isExists('/notfound/directory')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
