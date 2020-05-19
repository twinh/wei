isDir
=====

检查数据是否为存在的目录

案例
----

### 检查"/notfound/directory"是否为存在的目录

```php
if (wei()->isDir('/notfound/directory')) {
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

*无*

### 错误信息

名称                   | 信息
-----------------------|------
notStringMessage       | %name%必须是字符串
notFoundMessage        | %name%必须是存在的目录
negativeMessage        | %name%必须是不存在的目录

### 方法

#### isDir($input)
检查数据是否为存在的目录

相关链接
--------

* [验证器概览](../book/validators.md)
* [检查文件或目录是否存在:isExists](isExists.md)
* [检查文件是否存在:isFile](isFile.md)