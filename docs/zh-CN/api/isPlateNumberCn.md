[isPlateNumberCn()](http://twinh.github.io/widget/api/isPlateNumberCn)
======================================================================

检查数据是否为有效的中国车牌号码

### 
```php
bool isPlateNumberCn( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `pattern`             | %name%必须是正确的车牌格式                                     |
| `negative`            | %name%不能是正确的车牌格式                                     |
| `notString`           | %name%必须是字符串                                             |

##### 代码范例

检查"京A12345"是否为有效的车牌号码

```php 
if (wei()->isPlateNumberCn('京A12345')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```

相关链接
--------

* [验证器概览](../book/validators.md)