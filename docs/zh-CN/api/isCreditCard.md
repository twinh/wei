[isCreditCard()](http://twinh.github.com/widget/api/isCreditCard)
=================================================================

检查数据是否为合法的信用卡号码

### 检查数据是否为合法的信用卡号码
```php
bool isCreditCard( $input [, $type] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$type** `string|array` 指定信用卡类型,多个使用`,`隔开,或是使用数组,留空表示允许任意信用卡号.


允许指定的信用卡类型有: American Express, Diners Club, Discover, JCB, MasterCard, China UnionPay 和 Visa
下表为目前允许的信用卡类型
| **发卡机构**     | **中文名称** | **值**       |
|------------------|--------------|--------------|
| American Express | 美国运通     | `Amex`       |
| Diners Club      | 大来卡       | `DinersClub` |
| Discover Card    | 发现卡       | `Discover`   |
| JCB              | -            |`JCB`         |
| MasterCard       | 万事达卡     | `MasterCard` |
| China UnionPay   | 中国银联     | `UnionPay`   | 
| Visa             | -            | `Visa`       |

##### 范例
检查数据是否为Visa或银联卡,第二个参数即可以是字符串,也可以是数组

```php
<?php

$input = '4111111111111111'; // Visa

if ($widget->isCreditCard($input, 'UnionPay,Visa')) {
    echo 'Yes';
} else {
    echo 'No';
}

echo "\n";

if ($widget->isCreditCard($input, array('UnionPay', 'Visa'))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes
Yes'
```
