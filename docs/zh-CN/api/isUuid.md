[isUuid()](http://twinh.github.io/widget/api/isUuid)
====================================================

检查数据是否为有效的UUID

### 
```php
bool isUuid( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `pattern`             | %name%必须是有效的UUID                                         |
| `negative`            | %name%不能是有效的UUID                                         |
| `notString`           | %name%必须是字符串                                             |


##### 代码范例
检查"00010203-0405-0607-0809-0A0B0C0D0E0F"是否为有效的UUID
```php
<?php
 
if ($widget->isUuid('00010203-0405-0607-0809-0A0B0C0D0E0F')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
