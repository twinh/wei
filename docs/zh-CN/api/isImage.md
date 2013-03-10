[isImage()](http://twinh.github.com/widget/api/isImage)
=======================================================

检查数据是否为图片,同时还可以检查图片宽度和高度是否在指定的范围内

### 
```php
bool isImage( $input [, $options ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$options** `array` 选项数组,留空表示只检查数据是否为图片
   *  **maxWidth** `int` 允许的图片最大宽度
   *  **minWidth** `int` 允许的图片最小宽度
   *  **maxHeight** `int` 允许的图片最大高度
   *  **minHeight** `int` 允许的图片最小高度

##### 范例
检查文件"5x5.gif"是否为图片且最大高度不能超过5px
```php
<?php
 
if ($widget->isImage(__DIR__ . '/fixtures/5x5.gif', array('maxWidth' => 5))) {
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
检查文件"5x5.gif"是否为图片且后缀名不能为"gif"
```php
<?php
 
if ($widget->isImage(__DIR__ . '/fixtures/5x5.gif', array('excludeExts' => 'gif'))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 输出
```php
'No'
```
