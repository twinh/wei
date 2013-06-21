[isAfter()](http://twinh.github.io/widget/api/isAfter)
======================================================

检查数据是否大于等于指定的日期或时间

### 
```php
bool isAfter( $input [, $max])
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$max** `string` 用于比较的日期或时间


实际上,`isAfter`是`isMax`的别名.区别在于`isAfter`用于日期时间的比较,而`isMax`用于数据大小
的比较,针对不同的比较情况使用不同的验证器可增加代码可读性.


##### 代码范例
检查数据是否大于等于2013-01-01
```php
<?php
 
if (widget()->isAfter(date('Y-m-d'), '2013-01-01')) {
    echo 'Today is after 2013-01-01';
} else {
    echo 'Today is before 2013-01-01';
}
```
##### 运行结果
```php
'Today is after 2013-01-01'
```
