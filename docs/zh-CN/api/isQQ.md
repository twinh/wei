[isQQ()](http://twinh.github.com/widget/api/isQQ)
=================================================

检查数据是否为有效的QQ号码

### 
```php
bool isQQ ( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查"123456"是否为有效的QQ号码
```php
<?php
 
if ($widget->isQQ('123456')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
