[isFile()](http://twinh.github.com/widget/api/isFile)
=====================================================

检查数据是否为合法的文件,可选的检查选项有文件大小,后缀名和媒体类型

### 
```php
bool isFile( $input [, $options ] )
```

##### 参数
* **$input** `mixed` 待验证的数据
* **$options** `array` 选项数组,留空表示只检查数据是否为存在的文件
   *  **maxSize** `int` 允许的文件最大字节数
   *  **minSize** `int` 允许的文件最小字节数
   *  **exts** `string|array` 允许的文件扩展名
   *  **excludeExts** `string|array` 不允许的文件扩展名
   *  **mimeTypes** `string|array` 允许的文件媒体类型
   *  **excludeMimeTypes** `string|array` 不允许的文件媒体类型


第一个参数`$input`允许以下3种变量类型.

1. 字符串,变量的值是文件的路径,可以是相对路径,也可以是绝对路径
2. 数组,确切的说是来自文件上传变量`$_FILES`的子数组,该数组应该至少包括`tmp_name`和`name`两个键
3. `\SplFile`对象


##### 代码范例
检查文件"5x5.gif"是否为存在且大小小于1.2mb
```php
<?php
 
if ($widget->isImage(__DIR__ . '/fixtures/5x5.gif', array('maxSize' => '1.2mb'))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'Yes'
```
