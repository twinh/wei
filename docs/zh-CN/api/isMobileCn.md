[isMobileCn()](http://twinh.github.com/widget/api/isMobileCn)
=============================================================

检查数据是否为有效的手机号码

### 
```php
bool isMobileCn( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

isMobile
##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `pattern`             | %name%必须是13位长度的数字,以13,15或18开头                     |
| `negative`            | %name%必须不匹配模式"%pattern%"                                |
| `notString`           | %name%必须是字符串                                             |

