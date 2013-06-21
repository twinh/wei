isTld
=====

检查数据是否为存在的顶级域名

案例
----

### 检查"cn"是否为存在的顶级域名
```php
if (widget()->isTld('cn')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'Yes'
```

调用方式
--------

### 选项

| 名称              | 类型    | 默认值                           | 说明                       |
|-------------------|---------|----------------------------------|----------------------------|
| notStringMessage  | string  | %name%必须是字符串               | -                          |
| notInMessage      | string  | %name%必须是有效的顶级域名       | -                          |
| negativeMessage   | string  | %name%不能是有效的顶级域名       | -                          |

### 方法

#### isTld($input)
检查数据是否为存在的顶级域名
