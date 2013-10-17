验证器
======

所有的验证规则请查看[API目录](../#api参考目录)-[验证器](../#验证器)章节.

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

名称           | 类型    | 默认值  | 说明
---------------|---------|---------|------
negative       | bool    | false   | 是否为"反"验证器

### 通用错误信息

名称                   | 信息
-----------------------|------
notStringMessage       | %name%必须是字符串
negativeMessage        | %name%不合法
name                   | 该项

### 通用方法

#### getMessages($name = null)

获取验证错误信息

返回: `array`

#### getJoinedMessage($separator = "\n", $name = null)

获取合并的验证错误信息

返回: `string`

#### getFirstMessage($name = null)

获取第一条错误信息

返回: `string`

#### setMessages(array $messages)

设置错误时的提示信息

返回: `Widget\Validator\BaseValidator`

#### getName()

获取错误信息中的数据项名称

返回: `string`

#### setName()

设置错误信息中的数据项名称

返回: `string`