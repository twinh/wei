[isIp()](http://twinh.github.com/widget/api/isIp)
=================================================

检查数据是否为有效的IP地址

### 
```php
bool isIp ( $input [, $options ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$options** `array` 选项数组,留空表示只检查IP地址是否有效
   *  **ipv4** `bool` 是否只允许IPv4格式的IP地址
   *  **ipv6** `bool` 是否只允许IPv6格式的IP地址
   *  **noPrivRange** `bool` 是否不允许私有的IP地址
   *  **noResRange** `bool` 是否不允许保留的IP地址

##### 代码范例
检查"192.168.0.1"是否为有效的IP地址
```php
<?php
 
if ($widget->isIp('192.168.0.1')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
##### 代码范例
检查"192.168.0.1"是否不在私有的IP地址中
```php
<?php
 
if ($widget->isIp('192.168.0.1', array('noPrivRange' => true))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
