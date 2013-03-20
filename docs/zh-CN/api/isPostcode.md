[isPostcode()](http://twinh.github.com/widget/api/isPostcode)
=============================================================

检查数据是否为有效的中国邮政编码

### 
```php
bool isPostcode ( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 代码范例
检查"123456"是否为有效的车牌号码
```php
<?php
 
if ($widget->isPostcode('123456')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
