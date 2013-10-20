isChinese
=========

检查数据是否只由汉字组成

案例
----

### 检查数据是否只由汉字组成
```php
if (wei()->isChinese('中文')) {
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

| 名称                | 类型    | 默认值                           | 说明              |
|---------------------|---------|----------------------------------|-------------------|
| notStringMessage    | string  | %name%必须是字符串               | -                 |
| patternMessage      | string  | %name%只能由中文组成             | -                 |
| negativeMessage     | string  | %name%不能只由中文组成           | -                 |

### 方法

#### isChinese($input)
检查数据是否只由汉字组成

相关链接
--------

* [验证器概览](../book/validators.md)