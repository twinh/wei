isUppercase
===========

检查数据是否为大写字符

案例
----

### 检查"abc"是否为大写字符
```php
if (wei()->isLowercase('abc')) {
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

| 名称              | 类型    | 默认值                           | 说明                                             |
|-------------------|---------|----------------------------------|--------------------------------------------------|
| notStringMessage  | string  | %name%必须是字符串               | -                                                |
| invalidMessage    | string  | %name%不能包含小写字母           | -                                                |
| negativeMessage   | string  | %name%不能包含大写字母           | -                                                |

### 方法

#### isUppercase($input)
检查数据是否为大写字符

相关链接
--------

* [验证器概览](../book/validators.md)