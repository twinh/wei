Upload
======

保存客户端上传的文件到指定目录

案例
----

### 上传单个文件

```php
$result = wei()->upload();
if ($result) {
    echo 'Yes';
} else {
    print_r(wei()->upload->getMessages());
}
```

### 上传图片文件,限定宽度和高度小于2000px
```php
$result = wei()->upload(array(
    'maxHeight' => 2000,
    'maxWidth' => 2000
));

if ($result) {
    echo 'Yes';
} else {
    print_r(wei()->upload->getMessages());
}
```

### 在一个请求中批量上传文件
```php
for ($i = 0; $i < 3; $i++) {
    $result = wei()->upload(array(
        'field' => 'upload_' . $i
    ));
    if ($result) {
        echo '文件' . $i . '上传成功';
    } else {
        print_r(wei()->upload->getMessages());
    }
}
```

调用方式
--------

### 选项

| 名称                           | 类型       | 默认值    |  说明                                                           |
|--------------------------------|------------|-----------|-----------------------------------------------------------------|
| field                          | string     | null      | 上传表单中,文件上传控件name的值,留空将自动获取第一个name的值    |
| dir                            | string     | uploads   | 文件保存的目录,默认是`uploads`                                  |
| fileName                       | string     | nulll     | 文件保存时的名称(不包括扩展),留空表示使用原来的名称保存         |
| isImage                        | bool       | false     | 是否检查上传的文件为图片,默认为否                               |
| maxSize                        | int        | 0         | 允许的文件最大字节数,允许使用类似`10.5MB`, `500KB`的文件大小值  |
| minSize                        | int        | 0         | 允许的文件最小字节数,允许使用类似`10.5MB`, `500KB`的文件大小值  |
| exts                           | array      | array()   | 允许的文件扩展名                                                |
| excludeExts                    | array      | array()   | 不允许的文件扩展名                                              |
| mimeTypes                      | array      | array()   | 允许的文件媒体类型                                              |
| excludeMimeTypes               | array      | array()   | 不允许的文件媒体类型                                            |
| maxWidth                       | int        | 0         | 允许的图片最大宽度                                              |
| maxHeight                      | int        | 0         | 允许的图片最大高度                                              |
| minWidth                       | int        | 0         | 允许的图片最小宽度                                              |
| minHeight                      | int        | 0         | 允许的图片最小高度                                              |
| postSizeMessage                | string     | 没有文件被上传,或您上传的总文件大小超过%postMaxSize%           | -          |
| noFileMessage                  | string     | 没有文件被上传                                                 | -          |
| formLimitMessage               | string     | %name%的大小超过HTML表单设置                                   | -          |
| partialMessage                 | string     | %name%未完全上传,请再试一遍                                    | -          |
| noTmpDirMessage                | string     | 未找到上传文件的临时目录                                       | -          |
| cantWriteMessage               | string     | 无法保存%name%                                                 | -          |
| extensionMessage               | string     | 文件上传被扩展中止                                             | -          |
| notUploadedFileMessage         | string     | 没有文件被上传,请选择一个文件上传                              | -          |
| cantMoveMessage                | string     | 无法移动上传的文件                                             | -          |
| notDetectedMessage             | string     | %name%不是有效的图片,或是无法检测到图片的尺寸                  | -          |
| widthTooBigMessage             | string     | %name%的宽度太大(%width%px), 允许的最大宽度为%maxWidth%px      | -          |
| widthTooSmallMessage           | string     | %name%的宽度太小(%width%px),允许的最小宽度应为%minWidth%px     | -          |
| heightTooBigMessage            | string     | %name%的高度太大(%height%px), 允许的最大高度为%maxHeight%px    | -          |
| heightTooSmallMessage          | string     | %name%的高度太小(%height%px), 允许的最小高度为%minHeight%px    | -          |
| notFoundMessage                | string     | %name%不存在或不可读                                           | -          |
| maxSizeMessage                 | string     | %name%太大了(%sizeString%),允许的最大文件大小为%maxSizeString% | -          |
| minSizeMessage                 | string     | %name%太小了(%sizeString%),允许的最小文件大小为%minSizeString% | -          |
| extsMessage                    | string     | %name%的扩展名(%ext%)不合法,只允许扩展名为:%exts%              | -          |
| excludeExtsMessage             | string     | %name%的扩展名(%ext%)不合法,不允许扩展名为:%excludeExts%       | -          |
| mimeTypeNotDetectedMessage     | string     | 无法检测%name%的媒体类型                                       | -          |
| mimeTypesMessage               | string     | %name%的媒体类型不合法                                         | -          |
| excludeMimeTypesMessage        | string     | %name%的媒体类型不合法                                         | -          |
| negativeMessage                | string     | %name%必须是不存在的文件                                       | -          |
| notStringMessage               | string     | %name%必须是字符串                                             | -          |


### 方法

#### upload($options)
上传一个文件

#### upload->getMessages()
获取错误信息
