[isPlateNumberCn()](http://twinh.github.com/widget/api/isPlateNumberCn)
=======================================================================

检查数据是否为有效的中国车牌号码

### 
```php
bool isPlateNumberCn ( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 代码范例
检查"京A12345"是否为有效的车牌号码
```php
<?php
 
if ($widget->isPlateNumberCn('京A12345')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
