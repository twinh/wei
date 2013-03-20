[isDate()](http://twinh.github.com/widget/api/isDate)
=====================================================

检查数据是否为合法的日期

### 
```php
bool isDate( $input [, $format ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$format** `string` 日期格式,默认是"Y-m-d"


完整的日期格式可以查看PHP官方文档中关于[date](http://php.net/manual/zh/function.date.php)函数的格式说明.


##### 代码范例
检查"2013-01-01"是否为合法的日期
```php
<?php

if ($widget->isDate('2013-01-01')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
