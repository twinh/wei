isCallback
==========

检查数据是否通过指定回调方法验证

案例
----

### 通过回调方法检查数据是否能被3整除

```php
if (wei()->isCallback(3, function($input) {
    return 0 === 10 % $input;
})) {
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
invalidMessage         | %name%不合法

### 方法

#### isCallback($input, $fn, $message = null)
检查数据是否通过指定回调方法验证

名称                | 类型     | 默认值  | 说明
--------------------|----------|---------|------
$input              | mixed    | 无      | 待验证的数据
$fn                 | callback | 无      | 指定验证的回调结构
$message            | string   | 无      | 自定义验证不通过的提示信息

相关链接
--------

* [验证器概览](../book/validators.md)