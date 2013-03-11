[isUuid()](http://twinh.github.com/widget/api/isUuid)
=====================================================

检查数据是否为有效的UUID

### 
```php
bool isUuid( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 范例
检查"00010203-0405-0607-0809-0A0B0C0D0E0F"是否为有效的UUID
```php
<?php
 
if ($widget->isUuid('00010203-0405-0607-0809-0A0B0C0D0E0F')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
