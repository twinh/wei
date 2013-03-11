[isType()](http://twinh.github.com/widget/api/isType)
=====================================================

检查数据是否为指定的类型

### 
```php
bool isType( $input [, $type ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
$type string 指定的数据类型


如果`$type`的值不在下表中,将检查数据是否为`$type`的实例化对象($input instanceof $type)

| **值**   | **名称**             | **值**   | **名称**             |
|----------|----------------------|----------|----------------------|
| array    | 数组                 | alnum    | 字母(a-z)或数字(0-9) |
| bool     | 布尔                 | alpha    | 字母                 |
| float    | 浮点数               | cntrl    | 控制字符             |
| int      | 整型                 | digit    | 数字                 |
| integer  | 整型                 | graph    | 可显示字符           |
| null     | NULL                 | lower    | 小写字母(a-z)        |
| numeric  | 数字                 | print    | 可打印字符           |
| object   | 对象                 | punct    | 标点符号             |
| resource | 资源                 | space    | 空白字符             |
| scalar   | 标量                 | upper    | 大写字母(A-Z)        |
| string   | 字符串               | xdigit   | 16进制数字           |


##### 范例
检查"abc"是否为字符串
```php
<?php
 
if ($widget->isType('abc', 'string')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
##### 范例
检查new ArrayObject()是否为Traversable
```php
<?php
 
if ($widget->isType(new ArrayObject(), 'Traversable')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'Yes'
```
