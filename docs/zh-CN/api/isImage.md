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
   *  **maxSize** `int` 允许的文件最大字节数
   *  **minSize** `int` 允许的文件最小字节数
   *  **exts** `string|array` 允许的文件扩展名
   *  **excludeExts** `string|array` 不允许的文件扩展名
   *  **mimeTypes** `string|array` 允许的文件媒体类型
   *  **excludeMimeTypes** `string|array` 不允许的文件媒体类型


图片验证器是文件验证器[isFile](isFile.md)的子类,其中选项`maxSize`, `minSize`, `exts`,
`excludeExts`, `mimeTypes`, `excludeMimeTypes`继承自父类.

##### 错误信息
| **名称**              | **信息**                                                       | 
|-----------------------|----------------------------------------------------------------|
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
检查文件"5x5.gif"是否为图片且最大高度不能超过5px
```php
<?php
 
if ($widget->isImage(__DIR__ . '/fixtures/5x5.gif', array('maxWidth' => 5))) {
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
检查文件"5x5.gif"是否为图片且后缀名不能为"gif"
```php
<?php
 
if ($widget->isImage(__DIR__ . '/fixtures/5x5.gif', array('excludeExts' => 'gif'))) {
    echo 'Yes';
} else {
    echo 'No';
}
```
##### 运行结果
```php
'No'
```
