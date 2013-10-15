验证器
======

// TODO

案例
----

### 检查"example@example"是否为邮箱地址,如果不是,输出错误信息

```php
if (wei()->isEmail('example@example')) {
    echo 'Yes';
} else {
    print_r(wei()->isEmail->getMessages());
}

// 输出结果
array(
    'format' => '该项必须是有效的邮箱地址',
);
```

### 配置输出中文错误信息

默认输出的错误信息是英文的,只需配置翻译服务的区域为`zh-CN`即可输出中文错误信息.

```php
wei(array(
    't' => array(
        'locale' => 'zh-CN'
    ),
));
```

调用方式
--------

### 通用选项


### 通用方法