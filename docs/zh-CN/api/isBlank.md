[isBlank()](http://twinh.github.com/widget/api/isBlank)
=======================================================

检查数据是否为空(不允许空格)

##### 目录
* isBlank( $input )

### 检查数据是否为空(不允许空格)
```php
bool isBlank( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
```php
<?php

$input = '    ';
if ($widget->isBlank($input)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
