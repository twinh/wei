[isPostcode()](http://twinh.github.io/widget/api/isPostcode)
============================================================

检查数据是否为有效的中国邮政编码

### 
```php
bool isPostcode( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `pattern`             | %name%必须是6位长度的数字                                      |
| `negative`            | %name%不能是邮政编码                                           |
| `notString`           | %name%必须是字符串                                             |


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
