isExists
========

检查数据是否为存在的文件或目录

案例
----

### 检查路径"/notfound/directory"是否存在
```php
if (wei()->isExists('/notfound/directory')) {
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

| 名称                | 类型    | 默认值                                 | 说明              |
|---------------------|---------|----------------------------------------|-------------------|
| notStringMessage    | string  | %name%必须是字符串                     | -                 |
| notFoundMessage     | string  | %name%必须是存在的文件或目录           | -                 |
| negativeMessage     | string  | %name%必须是不存在的文件或目录         | -                 |

### 方法

#### isExists($input)
检查数据是否为存在的文件或目录

### 
```php
bool isExists( $input )
```

相关链接
--------

* [验证器概览](../book/validators.md)