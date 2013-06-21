[isBefore()](http://twinh.github.io/widget/api/isBefore)
========================================================

检查数据是否小于等于指定的日期或时间

### 
```php
bool isBefore( $input [, $min ])
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$min** `string` 用于比较的日期或时间


实际上,`isBefore`是`isMin`的别名.区别在于`isBefore`用于日期时间的比较,而`isMin`用于数据大小
的比较,针对不同的比较情况使用不同的验证器可增加代码可读性.


##### 代码范例
检查数据是否小于等于2020-01-01
```php
<?php
 
if (widget()->isBefore(date('Y-m-d'), '2020-01-01')) {
    echo 'Today is before 2020-01-01';
} else {
    echo 'Today is after 2020-01-01';
}
```
##### 运行结果
```php
'Today is before 2020-01-01'
```
