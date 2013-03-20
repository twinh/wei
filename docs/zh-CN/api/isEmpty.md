[isEmpty()](http://twinh.github.com/widget/api/isEmpty)
=======================================================

检查数据是否为空(允许空格)

### 
```php
bool isEmpty( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 代码范例
检查"abc"是否为空
```php
<?php

if ($widget->isEmpty('abc')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
