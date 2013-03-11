[escape()](http://twinh.github.com/widget/api/escape)
=====================================================

转义字符串中的特殊字符,以便安全的输出到网页中

### 
```php
bool escape($input [, $type [, $charset ] ] )
```

##### 参数
* **$input** `string` 待转义的字符串
* **$type** `string` 转义的类型,目前支持html和js两种转义方式,默认是html
* **$charset** `string` 转义HTML时的解码字符集,默认是UTF-8


转义HTML字符串也可以直接调用`$widget->escape->html($input, $charset)`

转义Javascript字符串可以直接调用`$widget->escape->js($input)`


##### 范例
转义HTML字符串"&lt;a href=&quot;#&quot;&gt;链接&lt;/a&gt;
```php
<?php

echo $widget->escape('<a href="#">链接</a>');
```
##### 输出
```php
'&lt;a href=&quot;#&quot;&gt;链接&lt;/a&gt;'
```
##### 范例
转义Javascript字符串"\'"
```php
<?php

echo $widget->escape->js("\'");
```
##### 输出
```php
'\\\''
```
