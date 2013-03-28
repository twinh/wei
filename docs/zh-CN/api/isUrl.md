[isUrl()](http://twinh.github.com/widget/api/isUrl)
===================================================

检查数据是否为有效的URL地址,可选的检查选项有"path"和"query"

### 
```php
bool %name%( $input [, $options] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$options** `array` 选项数组,留空表示只检查数据是否为URL地址
* 	**path** `bool` 是否要求URL带有路径,如http://www.example.com/path/part
* 	**query** `bool` 是否要求URL带有查询参数,如http://www.example/?query=string


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `invalid`             | %name%必须是有效的URL地址                                      |
| `negative`            | %name%不能是URL地址                                            |
| `notString`           | %name%必须是字符串                                             |


##### 代码范例
检查"http://www.example.com"是否为有效的URL地址
```php
<?php
 
if ($widget->isUrl('http://www.example.com')) {
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
检查"http://www.example.com"是否为有效的URL地址,要求带有查询参数
```php
<?php
 
if ($widget->isUrl('http://www.example.com', array('query' => true))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
