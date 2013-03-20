[isMobile()](http://twinh.github.com/widget/api/isMobile)
=========================================================

检查数据是否为有效的手机号码

### 
```php
bool isMobile ( $input )
```

##### 参数
* **$input** `mixed` 待验证的数据

##### 代码范例
检查"13800138000"是否为有效的手机号码
```php
<?php
 
if ($widget->isMobile('13800138000')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
