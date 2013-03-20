[isTld()](http://twinh.github.com/widget/api/isTld)
===================================================

检查数据是否为存在的顶级域名

### 
```php
bool isTld( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 代码范例
检查"cn"是否为存在的顶级域名
```php
<?php
 
if ($widget->isTld('cn')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
