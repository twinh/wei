[isAfter()](http://twinh.github.com/widget/api/isAfter)
=======================================================

检查数据是否大于等于指定的日期或时间

##### 目录
* isAfter( $input )

### 检查数据是否大于等于指定的日期或时间
```php
bool isAfter( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据


实际上,`isAfter`是`isMax`的别名.

##### 范例
```php
<?php
 
if ($widget->isAfter(date('Y-m-d'), '2013-01-01')) {
    echo 'Today is after 2013-01-01';
} else {
    echo 'Today is before 2013-01-01';
}
```
##### 输出
```php
'Today is after 2013-01-01'
```
