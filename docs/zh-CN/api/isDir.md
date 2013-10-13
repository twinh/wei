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

| 名称              | 类型      | 默认值                    | 说明  |
|-------------------|-----------|---------------------------|-------|
| notStringMessage  | string    | %name%必须是字符串        | -     |
| notFoundMessage   | string    | %name%必须是存在的目录    | -     |
| negativeMessage   | string    | %name%必须是不存在的目录  | -     |

### 方法

#### isDir($input)
检查数据是否为存在的目录
