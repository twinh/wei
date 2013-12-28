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

*无*

### 错误信息

名称                    | 信息
------------------------|------
notStringMessage        | %name%必须是字符串
patternMessage          | %name%只能由中文组成
negativeMessage         | %name%不能只由中文组成

### 方法

#### isChinese($input)
检查数据是否只由汉字组成

相关链接
--------

* [验证器概览](../book/validators.md)