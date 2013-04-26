isDir
=====

检查数据是否为存在的目录

代码范例
--------

### 检查"/notfound/directory"是否为存在的目录
```php
<?php

if ($widget->isDir('/notfound/directory')) {
    echo 'Yes';
} else {
    echo 'No';
}
```
### 运行结果
```php
'No'
```

调用方式
--------

### 选项

| 名称              | 类型      | 默认值                    | 说明                                                                      |
|-------------------|-----------|---------------------------|-------|
| notStringMessage  | string    | %name%必须是字符串        | *无*  |
| notFoundMessage   | string    | %name%必须是存在的目录    | *无*  |
| negativeMessage   | string    | %name%必须是不存在的目录  | *无*  |                                                       |

### 方法

#### isDir($input)
检查数据是否为存在的目录
