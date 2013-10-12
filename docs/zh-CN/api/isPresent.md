isPresent
=========

检查数据是否为空(允许空格)

案例
----

### 检查"abc"是否不为空

```php
if (wei()->isPresent('abc')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'Yes'
```

### 检查空格字符串是否不为空

```php
if (wei()->isPresent(' ')) {
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

名称                   | 信息
-----------------------|------
emptyMessage           | %name%必须为空
negativeMessage        | %name%不能为空

### 方法

#### isPresent($input)
检查数据是否不为空(允许空格)
