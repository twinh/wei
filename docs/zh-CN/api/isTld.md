isTld
=====

检查数据是否为存在的顶级域名

案例
----

### 检查"cn"是否为存在的顶级域名

```php
if (wei()->isTld('cn')) {
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

无

### 错误信息

名称                   | 信息
-----------------------|------
notStringMessage       |%name%必须是字符串
notInMessage           | %name%必须是有效的顶级域名
negativeMessage        | %name%不能是有效的顶级域名

### 方法

#### isTld($input)
检查数据是否为存在的顶级域名

相关链接
--------

* [验证器概览](../book/validators.md)