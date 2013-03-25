[isLowercase()](http://twinh.github.com/widget/api/isLowercase)
===============================================================

检查数据是否为小写字符

### 
```php
bool isLowercase( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `invalid`             | %name%不能包含大写字母                                         |
| `negative`            | %name%不能包含小写字母                                         |
| `notString`           | %name%必须是字符串                                             |

##### 代码范例
检查"abc"是否为小写字符
```php
<?php
 
if ($widget->isLowercase('abc')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
