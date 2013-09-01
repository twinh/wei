Validate
========

检查数组或对象中每一个元素是否能通过指定规则的验证

Widget验证器是参考[jQuery Validation](http://bassistance.de/jquery-plugins/jquery-plugin-validation/)
开发的数据验证器,她与`jQuery Validation`有着很多相似的地方,所以如果你使用过
`jQuery Validation`,使用Widget验证器将非常容易上手.

案例
----

### 检查数据是否符合指定的验证规则

```php
$validator = widget()->validate(array(
    'data' => array(
        'username' => 'tw',
        'email' => 'invalid',
    ),
    'rules' => array(
        'username' => array(
            'minLength' => 3,
            'alnum' => true,
        ),
        'email' => array(
            'email' => true
        )
    ),
    'names' => array(
        'username' => '用户名',
        'email' => '邮箱'
    ),
    'messages' => array(
        'username' => array(
            'minLength' => '%name%的长度必须大于3',
            'alnum' => '%name%只能由字母和数字组成'
        ),
        'email' => '%name%格式不正确'
    )
));

// 检查是否通过验证,输出'No'
if ($validator->isValid()) {
    echo 'Yes';
} else {
    echo 'No';
}

// 获取详细的错误信息
$detailMessages = $validator->getDetailMessages();

// 返回的信息如下
$detailMessages = array (
    'username' => array (
        'minLength' => array (
            'tooShort' => '用户名的长度必须大于3',
        ),
    ),
    'email' => array (
        'email' => array (
            'format' => '邮箱格式不正确',
        ),
    ),
);


// 获取简练的错误信息
$summaryMessages = $validator->getSummaryMessages();

// 返回的信息如下
$summaryMessages = array (
    'username' => array (
        0 => '用户名的长度必须大于3',
    ),
    'email' => array (
        0 => '邮箱格式不正确',
    ),
);

// 获取合并的错误信息
$joinedMessage = $validator->getJoinedMessage();

// 返回的信息如下
$joinedMessage = "用户名的长度必须大于3\n邮箱格式不正确";

// 获取第一条错误信息
$firstMessage = $validator->getFirstMessage();

// 返回的信息如下
$firstMessage = '用户名的长度必须大于3';
```

### 区分`required`,`notBlank`和`notEmpty`验证规则

这三个验证规则都用于检查数据不能为空,它们在使用场景和检查的数据内容稍有不同.

**使用场景的区别**

* required通常用于验证器的规则中,表示某一项是不能为空或是可选

    ```php
    $validator = widget()->validate(array(
        'rules' => array(
            'email' => array(
                'required' => true,
                'email' => true,
            )
        )
    ));
    ```
* notEmpty和notBlank既可以用于验证器规则中,又可以独立作为验证微件

    ```php
    // 作为验证器规则
    $validator = widget()->validate(array(
        'rules' => array(
            'email' => array(
                'required' => true,
                'notEmpty' => true,
                'email' => true,
            )
        )
    ));

    // 独立使用
    if ($this->isBlank($input)) {
        print_r($this->isBlank->getMessages());
    }
    if ($this->isEmpty($input)) {
        print_r($this->isEmpty->getMessages());
    }
    ```

**检查内容的区别**

* 当数据为以下值时,这三个规则均验证不通过,返回`false`

    * null
    * '' (空字符串)
    * false
    * array() (空数组)

* 当数据完全由以下字符组成时,`notBlank`规则会验证不通过,返回`false`

    字符   | ASCII | 说明
    -------|-------|------
    " "    | 32    | 普通空格符
    "\t"   | 9     | 制表符
    "\n"   | 10    | 换行符
    "\r"   | 13    | 回车符
    "\0"   | 0     | 空字节符
    "\x0B" | 11    | 垂直制表符

    来源参见[trim](http://www.php.net/manual/zh/function.trim.php)函数

* 当数据由其他字符组成(包括0),所有规则均验证通过,返回`true`

### 区分`all`和`allOf`验证规则

```php

```

调用方式
--------

### 选项

名称         | 类型         | 默认值  | 说明
-------------|--------------|---------|------
data         | array,object | array() | 待验证的数据,可以是数组或对象
rules        | array        | array() | 验证规则数组
names        | array        | array() | 数据项名称的数组,用于错误信息提示
breakRule    | bool         | false   | 是否当任意一项规则验证不通过时就中断验证流程
breakField   | bool         | false   | 是否当任意一项数据验证不通过时就中断验证流程
skip         | bool         | false   | 是否当任意一项数据中的一项规则不通过时,就跳转到下一项数据的验证流程,默认是false,启用后,每项数据最多会有一个未通过的验证规则
ruleValid    | callback     | 无      | 规则验证通过时调用的回调函数
ruleInvalid  | callback     | 无      | 规则验证不通过时调用的回调函数
fieldValid   | callback     | 无      | 数据项验证通过时调用的回调函数
fieldInvalid | callback     | 无      | 数据项验证不通过时调用的回调函数
success      | callback     | 无      | 验证器验证通过(所有验证规则都通过)时调用的回调函数
failure      | callback     | 无      | 验证器验证不通过(任意验证规则不通过)时调用的回调函数

#### 选项详细说明

#### data
待验证的数据,可以是数组或对象

验证数据的取值的顺序如下

1. 如果`$data`是数组或`\ArrayAccess`的实例化对象,检查并返回`$data[$key]`的值
2. 如果`$data`是对象,检查属性`$key`是否存在,存在则返回`$data->$key`的值
3. 如果`$data`是对象且方法`get. $key`存在,返回`$data->{'get' . $key}`
4. 如果以上均不存在,返回null

#### 代码范例
```php
class User
{
    public function getName()
    {
        return 'twin';
    }

    public function getEmail()
    {
        return 'test@test.com';
    }
}

// 以数组为验证数据
widget()->validate(array(
    'data' => array(
        'name' => 'twin',
        'email' => 'test@test.com'
    )
));

// 以数组对象为验证数据
widget()->validate(array(
    'data' => new \ArrayObject(array(
        'name' => 'twin',
        'email' => 'test@test.com'
    ))
));

// 以对象为验证数据
widget()->validate(array(
    'data' => new User
));
```

#### rules

验证规则数组.

规则可以字符串,表示一项验证规则,也可以是数组,表示多项验证规则.

**注意:** 

1. 所有数据项都是 **必选** 的,如果某一个数据项是选填的,只需增加 **`required => false`** 的验证规则
2. 验证规则会被转换成对应的类.如`email`规则将被转换为`\Widget\Validator\Email`类,如果类不存在,将抛出异常提醒开发人员规则不存在.
3. 验证规则都是 **不** 以`is`开头的,`email`,`digit`是正确的规则名称,`isEmail`,`isDigit`是错误的规则名称

#### 代码范例
```php
widget()->validate(array(
    'rules' => array(
        // 简单规则,将会被转换为 array('required' => true)
        'name' => 'required',
        // 复合规则
        'email' => array(
            'required' => false, // 设置为false表示email是选填
            'email' => true,
            'length' => array(3, 256),
        ),
        // 完整规则
        'avatar' => array(
            'image' => array(
                'maxWidth' => 200,
                'maxHeight' => 200
            )
        ),
        // 允许的验证器格式
        '数据项名称' => array(
            '验证器名称1',
            '验证器名称2' => array('验证的选项1', '验证的选项2'), // 参数将传递给验证器的__invoke方法
            '验证器名称3' => array( // 参数将传递给验证器的setOption方法
                '验证器选项名称1' => '验证器选项值1',
                '验证器选项名称2' => '验证器选项值2',
            )
        ) 
    )
));
```

#### 代码范例

区分验证规则和验证微件的名称

1. 验证微件均是以is开头,如isDigit,isAlnum
2. 作为验证规则时,需使用原始的名称,如digit,alnum

```php
$age = 18;

widget()->validate(array(
    'data' => array(
        'age' => $age
    ),
    'rules' => array(
        'age' => array(
            'digit' => true, // √正确
            'isDigit' => true, // ×错误
        )
    )
));

// 通过isDigit验证微件,验证数据
widget()->isDigit($age);

// 通过is微件,指定验证规则验证数据
widget()->is('digit', $age);
```

#### messages
验证错误时的提示信息.提示信息的格式与验证规则类似.

#### 代码范例
```php
widget()->validate(array(
    'rules' => array(
        'name' => 'required',
        'email' => array(
            'required' => true,
            'email' => true
        ),
        'avatar' => array(
            'image' => array(
                'maxWidth' => 200,
                'maxHeight' => 200
            )
        )
    ),
    'messages' => array(
        // 简单信息
        'name' => '请输入您的用户名',
        // 复合信息
        'email' => array(
            'required' => '请输入邮箱地址',
            'email' => '您输入的邮箱格式不正确'
        ),
        // 完整信息
        'avatar' => array(
            'image' => array(
                'widthTooBig' => '您的头像宽度不能超过200px',
                'heightTooBig' => '您的头像高度不能超过200px'
            )
        ),
        // 信息的格式
        '数据项名称1' => '数据项错误信息1',
        '数据项名称2' => array(
            '验证器名称1' => '验证器错误信息1',
            '验证器名称2' => '验证器错误信息2'
        ),
        '数据项名称3' => array(
            '验证器名称3' => array(
                '验证错误信息的名称1' => '验证错误信息1'
                '验证错误信息的名称2' => '验证错误信息2'
            )
        ),
    )
));
```

#### names
数据项名称的数组,用于错误信息提示.数组的键名是验证数据项的值,数值的值是验证数据项的名称.如

**注意:**如果未提供数据项名称,错误信息将以`该项`作为验证数据项的名称,完整的错误信息例如`该项不能为空`

#### 代码范例
```php
widget()->validate(array(
    'rules' => array(
        'name' => 'required',
        'email' => array(
            'required' => true,
            'email' => true
        ),
        'avatar' => array(
            'image' => array(
                'maxWidth' => 200,
                'maxHeight' => 200
            )
        )
    ),
    // 指定验证数据项的名称
    'names' => array(
        'name' => '用户名',
        'email' => '邮箱',
        'avatar' => '头像'
    )
));

// 输出信息类似:
// 用户名不能为空
// 邮箱不能为空
// 头像宽度不能超过200px
```

#### ruleValid
当任意一条规则验证通过时,验证器就会触发`ruleValid`回调.

**参数**

```php
ruleValid($rule, $field, $validator, $widget)
```

名称        | 类型                  | 说明
------------|-----------------------|------
$rule       | string                | 验证规则的名称
$field      | string                | 当前验证的数据项名称
$validator  | Widget\Validate       | 验证器对象
$widget     | Widget\Widget         | 微件管理器

如果`ruleValid`回调返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

#### 代码范例
```php
widget()->validate(array(
    'data' => array(
        'name' => 'twin'
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'ruleValid' => function($rule, $field, $validator, $widget) {
        echo $rule;
        echo $field;
        echo 'Yes';
    }
));
```

#### 运行结果

```php
'required'
'name'
'Yes'
```

#### ruleInvalid
当任意一条规则验证 **不** 通过时,验证器就会触发`ruleInvalid`回调.

**参数**

```php
ruleInvalid($rule, $field, $validator, $widget)
```

名称        | 类型                  | 说明
------------|-----------------------|------
$rule       | string                | 验证规则的名称
$field      | string                | 当前验证的数据项名称
$validator  | Widget\Validate       | 验证器对象
$widget     | Widget\Widget         | 微件管理器

`ruleInvalid`与`ruleValid`的行为一致.
同样的,如果`ruleInvalid`回调返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

#### 代码范例

```php
widget()->validate(array(
    'data' => array(
        'name' => null
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'ruleInvalid' => function($rule, $field, $validator, $widget) {
        echo $rule;
        echo $field;
        echo 'No';
    }
));
```

#### 运行结果

```php
'required'
'name'
'No'
```

#### fieldValid
当任意数据项验证通过时,验证器就会触发`fieldValid`回调.

**参数**

```php
fieldValid($field, $validator, $widget)
```

名称        | 类型                  | 说明
------------|-----------------------|------
$rule       | string                | 验证规则的名称
$validator  | Widget\Validate       | 验证器对象
$widget     | Widget\Widget         | 微件管理器

如果`fieldValid`回调返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

#### 代码范例

```php
widget()->validate(array(
    'data' => array(
        'name' => 'twin'
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'fieldValid' => function($field, $validator, $widget) {
        echo $field;
        echo 'Yes';
    }
));
```

#### 运行结果

```php
'name'
'Yes'
```

##### fieldInvalid
当任意数据项验证 **不** 通过时,验证器就会触发`fieldInvalid`回调.

**参数**

```php
fieldInvalid($field, $validator, $widget)
```

名称        | 类型                  | 说明
------------|-----------------------|------
$rule       | string                | 验证规则的名称
$validator  | Widget\Validate       | 验证器对象
$widget     | Widget\Widget         | 微件管理器

如果`fieldInvalid`回调返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

#### 代码范例

```php
widget()->validate(array(
    'data' => array(
        'name' => null
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'fieldValid' => function($field, $validator, $widget) {
        echo $field;
        echo 'No';
    }
));
```

#### 运行结果

```php
'name'
'No'
```

#### success
验证结束时,如果最终验证结果为通过,验证器就触发`success`回调.

```php
success($validator, $widget)
```

名称        | 类型                  | 说明
------------|-----------------------|------
$validator  | Widget\Validate       | 验证器对象
$widget     | Widget\Widget         | 微件管理器

#### 代码范例

```php
widget()->validate(array(
    'data' => array(
        'name' => 'twin'
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'success' => function($validator, $widget) {
        echo 'Yes';
    }
));
```

#### 运行结果

```php
'Yes'
```

#### failure
验证结束时,如果最终验证结果为 **不** 通过,验证器就触发`failure`回调.

```php
failure ( $event, widget(), $validator )
```

名称        | 类型                  | 说明
------------|-----------------------|------
$validator  | Widget\Validate       | 验证器对象
$widget     | Widget\Widget         | 微件管理器

#### 代码范例

```php
widget()->validate(array(
    'data' => array(
        'name' => null
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'failure' => function($validator, $widget) {
        echo 'No';
    }
));
```

#### 运行结果

```php
'No'
```

#### breakRule 
默认为`false`.设置为`true`后,当任意一项规则验证不通过时就中断验证流程,直接返回验证结果(false)

#### breakField
默认为`false`.设置为`true`后,当任意一项数据验证不通过时就中断验证流程,直接返回验证结果(false)

#### skip
默认为`false`.设置为`true`后,当任意一项数据中的一项规则不通过时,就跳转到下一项数据的验证流程.

因此,每项数据最多会有一个未通过的验证规则

### 方法

#### validate($options)

检查数组或对象中每一个元素是否能通过指定规则的验证

返回: `Widget\Validate` 新的验证器对象

参数

名称        | 类型         | 说明
------------|--------------|------
$options    | array        | 验证器的选项,完整内容请查看"调用方式"-"选项"章节

#### validate->addRule($field, $rule, $parameters)

为数据项增加新的验证规则

返回: `Widget\Validate` 验证器对象

参数

名称        | 类型         | 说明
------------|--------------|------
$field      | string       | 数据项的名称
$rule       | string       | 验证规则的名称
$parameters | string       | 验证规则的参数

#### validate->hasRule($field, $rule)

检查数据项是否包含指定的验证规则

返回: `bool`

参数

名称        | 类型         | 说明
------------|--------------|------
$field      | string       | 数据项的名称
$rule       | string       | 验证规则的名称

#### validate->removeRule($field, $rule)

删除指定数据项的验证规则

返回: `bool` 规则存在并删除返回`true`,不存在返回`false`

参数

名称        | 类型         | 说明
------------|--------------|------
$field      | string       | 数据项的名称
$rule       | string       | 验证规则的名称

#### validate->setData($data)

设置要验证的数据

返回: `Widget\Validate` 验证器对象

#### validate->getData()

获取要验证的数据

返回: `array`

#### validate->setNames($names)

设置数据项的名称

返回: `Widget\Validate` 验证器对象

#### validate->getNames()

获取数据项的名称

返回: `array`

#### validate->getFieldData($field)

获取要验证的数据项的值

返回: `mixed` 不存在时返回null

参数

名称        | 类型         | 说明
------------|--------------|------
$field      | string       | 数据项的名称

#### validate->setFieldData($field, $value)

设置要验证的数据项的值

返回: `Widget\Validate`

参数

名称        | 类型         | 说明
------------|--------------|------
$field      | string       | 数据项的名称
$value      | mixed        | 数据项的值

#### validate->setMessages($messages)

设置自定义的错误信息

返回: `Widget\Validate`

参数

名称        | 类型         | 说明
------------|--------------|------
$messages   | array        | 错误信息数组

#### validate->getMessages()

获取自定义的错误信息

返回: `array` 

#### validate->getDetailMessages()

获取详细的验证错误信息

返回: `array` 

#### validate->getSummaryMessages()

获取简洁的验证错误信息

返回: `array` 

#### validate->getJoinedMessage($separator = "\n")

获取合并的验证错误信息

返回: `string`

参数

名称        | 类型         | 说明
------------|--------------|------
$separator  | string       | 合并错误信息数组的分隔符

#### validate->getFirstMessage()

获取第一条验证错误信息

返回: `string`|`false` 验证不通过时返回第一条错误信息,通过时返回`false`

#### validate->getRuleValidator($field, $rule)

获取规则验证器对象

返回: `Widget\Validator\AbstractValidator`|`null` 验证器不存在时返回null

参数

名称        | 类型         | 说明
------------|--------------|------
$field      | string       | 数据项的名称
$rule       | string       | 验证规则的名称