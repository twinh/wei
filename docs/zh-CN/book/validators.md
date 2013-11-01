验证器概览
==========

微框架提供了两种验证器.

一种用于验证表单提交的数据,使用的是[validate服务](../api/validate.md).

另外一种用于单个变量的验证,如检查变量的值是否为邮箱地址,手机号码等,所有的验证规则请查看[API目录](../#api参考目录)-[验证器](../#验证器)章节.

案例
----

### 检查表单提交的数据是否符合指定的验证规则

```php
$validator = wei()->validate(array(
    // 待验证的数据
    'data' => $_POST,
    // 验证规则数组
    'rules' => array(
        'username' => array(
            'minLength' => 3,
            'alnum' => true,
        ),
        'email' => array(
            'email' => true
        )
    )
));

// 如果验证不通过,输出详细的错误信息
if ($validator->isValid()) {
    print_r($validator->getDetailMessages());
}
```

完整的例子请查看[validate服务](../api/validate.md)-[案例](../api/validate.md#案例)章节

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

返回: `Wei\Validator\BaseValidator`

#### getName()

获取错误信息中的数据项名称

返回: `string`

#### setName()

设置错误信息中的数据项名称

返回: `string`


相关链接
--------

* [案例:使用`反`验证规则](../api/validate.md#%E6%A1%88%E4%BE%8B%E4%BD%BF%E7%94%A8%E5%8F%8D%E9%AA%8C%E8%AF%81%E8%A7%84%E5%88%99)
