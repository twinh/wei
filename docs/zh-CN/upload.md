[upload()](http://twinh.github.io/widget/api/upload)
====================================================

保存客户端上传的文件到指定目录

### 
```php
bool upload( $options )
```

##### 参数
* $options 上传微件的配置选项
   *  **field** `string` 上传表单中,文件上传控件name的值,留空将自动获取第一个name的值
   *  **dir** `string` 文件保存的目录,默认是`uploads`
   *  **fileName** `string` 文件保存时的名称(不包括扩展),留空表示使用原来的名称保存
   *  **isImage** `bool` 是否检查上传的文件为图片,默认为否
   *  **maxSize** `int` 允许的文件最大字节数
   *  **minSize** `int` 允许的文件最小字节数
   *  **exts** `string|array` 允许的文件扩展名
   *  **excludeExts** `string|array` 不允许的文件扩展名
   *  **mimeTypes** `string|array` 允许的文件媒体类型
   *  **excludeMimeTypes** `string|array` 不允许的文件媒体类型
   *  **maxWidth** `int` 允许的图片最大宽度
   *  **minWidth** `int` 允许的图片最小宽度
   *  **maxHeight** `int` 允许的图片最大高度
   *  **minHeight** `int` 允许的图片最小高度


上传微件是图片([isImage](isImage.md))和文件([isFile](isFile.md))验证器的子类,所以支持验证器的所有特性,如获取错误信息等.

上传微件是针对一个文件上传,如果需要在一次请求中批量上传文件,只需多次调用即可,详情请查看下面的例子


##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
| `postSize`            | 没有文件被上传,或您上传的总文件大小超过%postMaxSize%           |
| `noFile`              | 没有文件被上传                                                 |
| `formLimit`           | %name%的大小超过HTML表单设置                                   |
| `partial`             | %name%未完全上传,请再试一遍                                    |
| `noTmpDir`            | 未找到上传文件的临时目录                                       |
| `cantWrite`           | 无法保存%name%                                                 |
| `extension`           | 文件上传被扩展中止                                             |
| `notUploadedFile`     | 没有文件被上传,请选择一个文件上传                              |
| `cantMove`            | 无法移动上传的文件                                             |
| `notDetected`         | %name%不是有效的图片,或是无法检测到图片的尺寸                  |
| `widthTooBig`         | %name%的宽度太大(%width%px), 允许的最大宽度为%maxWidth%px      |
| `widthTooSmall`       | %name%的宽度太小(%width%px),允许的最小宽度应为%minWidth%px     |
| `heightTooBig`        | %name%的高度太大(%height%px), 允许的最大高度为%maxHeight%px    |
| `heightTooSmall`      | %name%的高度太小(%height%px), 允许的最小高度为%minHeight%px    |
| `notFound`            | %name%不存在或不可读                                           |
| `maxSize`             | %name%太大了(%sizeString%),允许的最大文件大小为%maxSizeString% |
| `minSize`             | %name%太小了(%sizeString%),允许的最小文件大小为%minSizeString% |
| `exts`                | %name%的扩展名(%ext%)不合法,只允许扩展名为:%exts%              |
| `excludeExts`         | %name%的扩展名(%ext%)不合法,不允许扩展名为:%excludeExts%       |
| `mimeTypeNotDetected` | 无法检测%name%的媒体类型                                       |
| `mimeTypes`           | %name%的媒体类型不合法                                         |
| `excludeMimeTypes`    | %name%的媒体类型不合法                                         |
| `negative`            | %name%必须是不存在的文件                                       |
| `notString`           | %name%必须是字符串                                             |



##### 代码范例
上传文件
```php
<?php

$result = $widget->upload();
if ($result) {
    echo 'Yes';
} else {
    print_r($widget->upload->getMessages());
}

```
##### 运行结果
```php
'Array
(
    [postSize] => Seems that the total file size is larger than the max size (20M) of allowed post data, please check the size of your file
)
'
```
##### 代码范例
指定上传的文件为图片,宽度和高度小于2000px
```php
<?php

$result = $widget->upload(array(
    'maxHeight' => 2000,
    'maxWidth' => 2000
));

if ($result) {
    echo 'Yes';
} else {
    print_r($widget->upload->getMessages());
}

```
##### 运行结果
```php
'Array
(
    [postSize] => Seems that the total file size is larger than the max size (20M) of allowed post data, please check the size of your file
)
'
```
##### 代码范例
在一个请求中批量上传文件
```php
<?php

for ($i = 0; $i < 3; $i++) {
    $result = $widget->upload(array(
        'field' => 'upload_' . $i
    ));
    if ($result) {
        echo '文件' . $i . '上传成功';
    } else {
        print_r($widget->upload->getMessages());
    }
}
```
##### 运行结果
```php
'Array
(
    [postSize] => Seems that the total file size is larger than the max size (20M) of allowed post data, please check the size of your file
)
Array
(
    [postSize] => Seems that the total file size is larger than the max size (20M) of allowed post data, please check the size of your file
)
Array
(
    [postSize] => Seems that the total file size is larger than the max size (20M) of allowed post data, please check the size of your file
)
'
```
