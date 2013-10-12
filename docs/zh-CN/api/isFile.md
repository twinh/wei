isFile
======

检查数据是否为合法的文件,可选的检查选项有文件大小,扩展名和媒体类型

案例
----

### 检查文件"5x5.gif"是否为存在且大小小于1.2mb
```php
if (wei()->isImage('./5x5.gif', array('maxSize' => '1.2mb'))) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'Yes'
```

调用方法
--------

### 选项

| 名称                           | 类型       | 默认值    |  说明                                                           |
|--------------------------------|------------|-----------|-----------------------------------------------------------------|
| maxSize                        | int        | 0         | 允许的文件最大字节数,允许使用类似`10.5MB`, `500KB`的文件大小值  |
| minSize                        | int        | 0         | 允许的文件最小字节数,允许使用类似`10.5MB`, `500KB`的文件大小值  |
| exts                           | array      | array()   | 允许的文件扩展名                                                |
| excludeExts                    | array      | array()   | 不允许的文件扩展名                                              |
| mimeTypes                      | array      | array()   | 允许的文件媒体类型                                              |
| excludeMimeTypes               | array      | array()   | 不允许的文件媒体类型                                            |
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

#### isFile($input, $options)
检查数据是否为合法的文件,可选的检查选项有文件大小,扩展名和媒体类型

第一个参数`$input`允许以下3种变量类型.

1. 字符串,变量的值是文件的路径,可以是相对路径,也可以是绝对路径
2. 数组,确切的说是来自文件上传变量`$_FILES`的子数组,该数组应该至少包括`tmp_name`和`name`两个键
3. `\SplFile`对象
