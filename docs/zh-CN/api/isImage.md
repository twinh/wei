isImage
=======

检查数据是否为图片,同时还可以检查图片宽度和高度是否在指定的范围内

案例
----

### 检查文件"5x5.gif"是否为图片且最大高度不能超过5px

```php
if (wei()->isImage('./5x5.gif', array('maxWidth' => 5))) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'Yes'
```

### 检查文件"5x5.gif"是否为图片且后缀名不能为"gif"

```php
if (wei()->isImage('./5x5.gif', array('excludeExts' => 'gif'))) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'No'
```

调用方式
--------

### 选项

名称             | 类型       | 默认值    |  说明
-----------------|------------|-----------|-------
maxSize          | int        | 0         | 允许的文件最大字节数,允许使用类似`10.5MB`, `500KB`的文件大小值
minSize          | int        | 0         | 允许的文件最小字节数,允许使用类似`10.5MB`, `500KB`的文件大小值
exts             | array      | array()   | 允许的文件扩展名
excludeExts      | array      | array()   | 不允许的文件扩展名
mimeTypes        | array      | array()   | 允许的文件媒体类型
excludeMimeTypes | array      | array()   | 不允许的文件媒体类型
maxWidth         | int        | 0         | 允许的图片最大宽度
maxHeight        | int        | 0         | 允许的图片最大高度
minWidth         | int        | 0         | 允许的图片最小宽度
minHeight        | int        | 0         | 允许的图片最小高度

### 错误信息

名称                       | 信息
---------------------------|------
notStringMessage           | %name%必须是字符串
notFoundMessage            | %name%不存在或不可读
maxSizeMessage             | %name%太大了(%sizeString%),允许的最大文件大小为%maxSizeString%
minSizeMessage             | %name%太小了(%sizeString%),允许的最小文件大小为%minSizeString%
extsMessage                | %name%的扩展名(%ext%)不合法,只允许扩展名为:%exts%
excludeExtsMessage         | %name%的扩展名(%ext%)不合法,不允许扩展名为:%excludeExts%
mimeTypeNotDetectedMessage | 无法检测%name%的媒体类型
mimeTypesMessage           | %name%的媒体类型不合法
excludeMimeTypesMessage    | %name%的媒体类型不合法
negativeMessage            | %name%必须是不存在的文件
notDetectedMessage         | %name%不是有效的图片,或是无法检测到图片的尺寸
widthTooBigMessage         | %name%的宽度太大(%width%px), 允许的最大宽度为%maxWidth%px
widthTooSmallMessage       | %name%的宽度太小(%width%px),允许的最小宽度应为%minWidth%px
heightTooBigMessage        | %name%的高度太大(%height%px), 允许的最大高度为%maxHeight%px
heightTooSmallMessage      | %name%的高度太小(%height%px), 允许的最小高度为%minHeight%px

### 方法

#### isImage($input, $options = array())
检查数据是否为图片,同时还可以检查图片宽度和高度是否在指定的范围内

图片验证器是文件验证器[isFile](isFile.md)的子类,其中选项`maxSize`, `minSize`, `exts`,
`excludeExts`, `mimeTypes`, `excludeMimeTypes`继承自父类.

相关链接
--------

* [验证器概览](../book/validators.md)