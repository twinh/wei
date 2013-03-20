[isNull()](http://twinh.github.com/widget/api/isNull)
=====================================================

检查数据是否为null

### 
```php
bool isNull ( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 代码范例
检查0是否为null
```php
<?php
 
if ($widget->isNull(0)) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
