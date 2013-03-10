[isPhone()](http://twinh.github.com/widget/api/isPhone)
=======================================================

检查数据是否为有效的电话号码

### 
```php
bool isPhone ( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查"020-1234567"是否为电话号码
```php
<?php
 
if ($widget->isPhone("020-1234567")) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
