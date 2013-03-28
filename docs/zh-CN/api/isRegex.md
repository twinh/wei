[isRegex()](http://twinh.github.com/widget/api/isRegex)
=======================================================

检查数据是否匹配指定的正则表达式

### 
```php
bool isRegex( $input [, $pattern ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$pattern** `string` 校验的正则表达式


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `pattern`             | %name%必须匹配模式"%pattern%"                                  |
| `negative`            | %name%必须不匹配模式"%pattern%"                                |
| `notString`           | %name%必须是字符串                                             |


##### 代码范例
检查"abc"是否匹配正则表达式"/d/i"
```php
<?php
 
if ($widget->isRegex('abc', '/d/i')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
