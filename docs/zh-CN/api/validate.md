Validate
========

检查数组或对象中每一个元素是否能通过指定规则的验证

`validate`服务是参考[jQuery Validation](http://jqueryvalidation.org/)开发的数据验证器,如果你使用过
`jQuery Validation`,使用`validate`服务将非常容易上手.

案例
----

### 检查数据是否符合指定的验证规则

```php
$validator = wei()->validate(array(
    // 待验证的数据
    'data' => array(
        'username' => 'tw',
        'email' => 'invalid',
    ),
    // 验证规则数组
    'rules' => array(
        'username' => array(
            'minLength' => 3,
            'alnum' => true,
        ),
        'email' => array(
            'email' => true
        )
    ),
    // 数据项名称的数组,用于错误信息提示
    'names' => array(
        'username' => '用户名',
        'email' => '邮箱'
    ),
    // 验证错误时的提示信息,留空将使用默认信息
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

// 获取第一条错误信息
$firstMessage = $validator->getFirstMessage();

// 返回的信息如下
$firstMessage = '用户名的长度必须大于3';

// 获取合并的错误信息
$joinedMessage = $validator->getJoinedMessage();

// 返回的信息如下
$joinedMessage = "用户名的长度必须大于3\n邮箱格式不正确";

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
```

调用方式
--------

### 选项

名称         | 类型         | 默认值  | 说明
-------------|--------------|---------|------
data         | array,object | array() | 待验证的数据,可以是数组或对象
rules        | array        | array() | 验证规则数组
names        | array        | array() | 数据项名称的数组,用于错误信息提示
messages     | array        | array() | 验证错误时的提示信息
breakRule    | bool         | false   | 是否当任意一项规则验证不通过时就中断验证流程
breakField   | bool         | false   | 是否当任意一项数据验证不通过时就中断验证流程
skip         | bool         | false   | 是否当任意一项数据中的一项规则不通过时,就跳转到下一项数据的验证流程,默认是false,启用后,每项数据最多会有一个未通过的验证规则
ruleValid    | callable     | 无      | 规则验证通过时调用的回调函数
ruleInvalid  | callable     | 无      | 规则验证不通过时调用的回调函数
fieldValid   | callable     | 无      | 数据项验证通过时调用的回调函数
fieldInvalid | callable     | 无      | 数据项验证不通过时调用的回调函数
success      | callable     | 无      | 验证器验证通过(所有验证规则都通过)时调用的回调函数
failure      | callable     | 无      | 验证器验证不通过(任意验证规则不通过)时调用的回调函数

#### 选项详细说明

#### 选项:data

待验证的数据,可以是数组或对象

验证数据的取值的顺序如下

1. 如果`$data`是数组或`\ArrayAccess`的实例化对象,检查并返回`$data[$key]`的值
2. 如果`$data`是对象,检查属性`$key`是否存在,存在则返回`$data->$key`的值
3. 如果`$data`是对象且方法`get. $key`存在,返回`$data->{'get' . $key}`
4. 如果以上均不存在,返回null

##### 案例:使用数组或对象作为验证数据

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
wei()->validate(array(
    'data' => array(
        'name' => 'twin',
        'email' => 'test@test.com'
    )
));

// 以数组对象为验证数据
wei()->validate(array(
    'data' => new \ArrayObject(array(
        'name' => 'twin',
        'email' => 'test@test.com'
    ))
));

// 以对象为验证数据
wei()->validate(array(
    'data' => new User
));
```

#### 选项:rules

验证规则数组.

规则可以字符串,表示一项验证规则,也可以是数组,表示多项验证规则.

所有的验证规则请查看[API目录](../#api参考目录)-[验证器](../#验证器)章节.

**注意:**

1. 所有数据项默认都是 **必选** 的,如果某一个数据项是选填的,只需增加 **`required => false`** 的验证规则
2. 验证规则会被转换成对应的类.如`email`规则将被转换为`\Wei\Validator\Email`类,如果类不存在,将抛出异常提醒开发人员规则不存在.
3. 验证规则都是 **不** 以`is`开头的,`email`,`digit`是正确的规则名称,`isEmail`,`isDigit`是错误的规则名称

##### 案例:验证规则格式

```php
wei()->validate(array(
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

##### 案例:区分验证规则和验证对象的名称

1. 验证对象均是以`is`开头,如`isDigit`,`isAlnum`
2. 作为验证规则时,需使用原始的名称,如`digit`,`alnum`

```php
$age = 18;

wei()->validate(array(
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

// 通过isDigit验证对象,验证数据
$bool = wei()->isDigit($age);
```

##### 案例:使用`反`验证规则

在验证规则前面加`not`,即可使验证规则返回相反的结果,同时输出的错误信息也会做相应的改变.

如注册表单中,要求检查用户名不存在,只需使用`notRecordExists`规则.

```php
// 配置数据库
wei(array(
    'db' => array(
        'dbname' => 'project',
        'user' => 'root',
        'password' => 'xxxxxx'
    )
));

$validator = wei()->validate(array(
    'data' => array(
        'username' => 'twin'
    ),
    'rules' => array(
        'username' => array(
            // 在user表中查询userame字段的值为twin,如果找到就返回false,找不到返回true
            'notRecordExists' => array('user', 'username'),
        )
    )
));

if (!$validator->isValid()) {
    print_r($validator->getSummaryMessages());
}

// 如果user表已经存在username为twin的记录,将输出如下错误信息
array(
    'username' => array(
        '该项已存在'
    )
);
```

使用`notBlank`规则检查输入内容不能为空(不允许空白字符)

```php
$validator = wei()->validate(array(
    'data' => array(
        'content' => ' '
    ),
    'rules' => array(
        'content' => array(
            'notBlank' => true
        )
    ),
    'names' => array(
        'content' => '内容'
    )
));

if (!$validator->isValid()) {
    print_r($validator->getSummaryMessages());
}

// 输出结果
array(
    'content' => array(
        '内容不能为空'
    )
);
```

##### 案例:区分`required`,`notBlank`和`present`验证规则

这三个验证规则都用于检查数据不能为空,它们在使用场景和检查的数据内容稍有不同.

**使用场景的区别**

* `required`通常用于验证器的规则中,表示某一项是不能为空或是可选

    ```php
    $validator = wei()->validate(array(
        'rules' => array(
            'email' => array(
                'required' => true,
                'email' => true,
            )
        )
    ));
    ```
* `present`和`notBlank`既可以用于验证器规则中,又可以独立作为验证对象

    ```php
    // 作为验证器规则
    $validator = wei()->validate(array(
        'rules' => array(
            'email' => array(
                'required' => true,
                'present' => true,
                'email' => true,
            )
        )
    ));

    // 独立使用,注意不以`not`开头
    if ($this->isBlank($input)) {
        print_r($this->isBlank->getMessages());
    }
    if ($this->isPresent($input)) {
        print_r($this->isPresent->getMessages());
    }
    ```

**检查内容的区别**

* 当数据为以下值时,这三个规则均验证不通过,返回`false`

    * null
    * '' (空字符串)
    * false
    * array() (空数组)

* 当数据完全由空白字符组成时,`notBlank`规则会验证不通过,返回`false`

    空白字符如下表:

    字符   | ASCII | 说明
    -------|-------|------
    " "    | 32    | 普通空格符
    "\t"   | 9     | 制表符
    "\n"   | 10    | 换行符
    "\r"   | 13    | 回车符
    "\0"   | 0     | 空字节符
    "\x0B" | 11    | 垂直制表符

    来源参见[trim](http://www.php.net/manual/zh/function.trim.php)函数

* 当数据由其他字符组成(包括字符串`"0"`和数字`0`),所有规则均验证通过,返回`true`

##### 案例:区分`all`和`allOf`验证规则

规则说明:

* `all`: 检查`数组`里的每一项是否符合指定的规则
* `allOf`: 检查数据是否通过所有的规则校验

它们的区别在于`all`的检查数据必须是数组,检查的对象是数组里的每一项,`allOf`可以是任意值

**检查数组里的每一项是否都为数字,并且小于8**

```php
$input = array(3, 2, 5);
if (wei()->isAll($input, array(
    'digit' => true,
    'lessThan' => 8
))) {
    echo 'Yes';
} else {
    echo 'No';
}
// 输出Yes
```

**检查数据是否为数字,并且小于8**

```php
$input = 3;
if (wei()->isAllOf($input, array(
    'digit' => true,
    'lessThan' => 8
))) {
    echo 'Yes';
} else {
    echo 'No';
}
// 输出Yes
```

##### 案例:使用`callback`验证器自定义验证逻辑

如果自带的验证规则不满足要求,可以是使用`callback`规则,自定义验证逻辑和错误信息.

```php
$validator = wei()->validate(array(
    'data' => array(
        'age' => 10
    ),
    'rules' => array(
        'age' => array(
            'callback' => function($data, \Wei\Validator\Callback $callback, $wei) {
                if ($data < 18) {
                    $callback->setMessage('您还未满18周岁');
                    return false;
                } else {
                    return true;
                }
            }
        )
    )
));

if ($validator->isValid()) {
    echo '$validator->getFirstMessage()';
}

// 输出
'您还未满18周岁'
```

#### 选项:messages

验证错误时的提示信息.提示信息的格式与验证规则类似.

##### 案例:提示信息的格式

```php
wei()->validate(array(
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

#### 选项:names

数据项名称的数组,用于错误信息提示.数组的键名是验证数据项的值,数值的值是验证数据项的名称.如

**注意:**如果未提供数据项名称,错误信息将以`该项`作为验证数据项的名称,完整的错误信息例如`该项不能为空`

##### 案例

```php
wei()->validate(array(
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

#### 回调:ruleValid

当任意一条规则验证通过时,验证器就会触发`ruleValid`回调.

**参数**

```php
ruleValid($rule, $field, $validator, $wei)
```

名称        | 类型                  | 说明
------------|-----------------------|------
$rule       | string                | 验证规则的名称
$field      | string                | 当前验证的数据项名称
$validator  | Wei\Validate       | 验证器对象
$wei     | Wei\Wei         | 对象管理器

如果`ruleValid`回调返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

##### 案例

```php
wei()->validate(array(
    'data' => array(
        'name' => 'twin'
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'ruleValid' => function($rule, $field, $validator, $wei) {
        echo $rule;
        echo $field;
        echo 'Yes';
    }
));
```

##### 运行结果

```php
'required'
'name'
'Yes'
```

#### 回调:ruleInvalid

当任意一条规则验证 **不** 通过时,验证器就会触发`ruleInvalid`回调.

**参数**

```php
ruleInvalid($rule, $field, $validator, $wei)
```

名称        | 类型                  | 说明
------------|-----------------------|------
$rule       | string                | 验证规则的名称
$field      | string                | 当前验证的数据项名称
$validator  | Wei\Validate       | 验证器对象
$wei     | Wei\Wei         | 对象管理器

`ruleInvalid`与`ruleValid`的行为一致.
同样的,如果`ruleInvalid`回调返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

##### 案例

```php
wei()->validate(array(
    'data' => array(
        'name' => null
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'ruleInvalid' => function($rule, $field, $validator, $wei) {
        echo $rule;
        echo $field;
        echo 'No';
    }
));
```

##### 运行结果

```php
'required'
'name'
'No'
```

#### 回调:fieldValid

当任意数据项验证通过时,验证器就会触发`fieldValid`回调.

**参数**

```php
fieldValid($field, $validator, $wei)
```

名称        | 类型                  | 说明
------------|-----------------------|------
$rule       | string                | 验证规则的名称
$validator  | Wei\Validate       | 验证器对象
$wei     | Wei\Wei         | 对象管理器

如果`fieldValid`回调返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

##### 案例

```php
wei()->validate(array(
    'data' => array(
        'name' => 'twin'
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'fieldValid' => function($field, $validator, $wei) {
        echo $field;
        echo 'Yes';
    }
));
```

##### 运行结果

```php
'name'
'Yes'
```

##### 回调:fieldInvalid

当任意数据项验证 **不** 通过时,验证器就会触发`fieldInvalid`回调.

**参数**

```php
fieldInvalid($field, $validator, $wei)
```

名称        | 类型                  | 说明
------------|-----------------------|------
$rule       | string                | 验证规则的名称
$validator  | Wei\Validate       | 验证器对象
$wei     | Wei\Wei         | 对象管理器

如果`fieldInvalid`回调返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

##### 案例

```php
wei()->validate(array(
    'data' => array(
        'name' => null
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'fieldValid' => function($field, $validator, $wei) {
        echo $field;
        echo 'No';
    }
));
```

##### 运行结果

```php
'name'
'No'
```

#### 回调:success

验证结束时,如果最终验证结果为通过,验证器就触发`success`回调.

```php
success($validator, $wei)
```

名称        | 类型                  | 说明
------------|-----------------------|------
$validator  | Wei\Validate       | 验证器对象
$wei     | Wei\Wei         | 对象管理器

##### 案例

```php
wei()->validate(array(
    'data' => array(
        'name' => 'twin'
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'success' => function($validator, $wei) {
        echo 'Yes';
    }
));
```

##### 运行结果

```php
'Yes'
```

#### 回调:failure

验证结束时,如果最终验证结果为 **不** 通过,验证器就触发`failure`回调.

```php
failure ( $event, $wei, $validator )
```

名称        | 类型                  | 说明
------------|-----------------------|------
$validator  | Wei\Validate       | 验证器对象
$wei     | Wei\Wei         | 对象管理器

##### 案例

```php
wei()->validate(array(
    'data' => array(
        'name' => null
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'failure' => function($validator, $wei) {
        echo 'No';
    }
));
```

##### 运行结果

```php
'No'
```

#### 选项:breakRule

默认为`false`.设置为`true`后,当任意一项规则验证不通过时就中断验证流程,直接返回验证结果(false)

#### 选项:breakField

默认为`false`.设置为`true`后,当任意一项数据验证不通过时就中断验证流程,直接返回验证结果(false)

#### 选项:skip

默认为`false`.设置为`true`后,当任意一项数据中的一项规则不通过时,就跳转到下一项数据的验证流程.

因此,每项数据最多会有一个未通过的验证规则

### 方法

#### validate($options)

检查数组或对象中每一个元素是否能通过指定规则的验证

返回: `Wei\Validate` 新的验证器对象

参数

名称        | 类型         | 说明
------------|--------------|------
$options    | array        | 验证器的选项,完整内容请查看"调用方式"-"选项"章节

#### validate->addRule($field, $rule, $parameters)

为数据项增加新的验证规则

返回: `Wei\Validate` 验证器对象

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

#### validate->setRules(array $rules)

设置验证规则

返回: `Wei\Validate` 验证器对象

#### validate->getRules()

获取验证规则

返回: `array` 验证规则数组

#### validate->setData($data)

设置要验证的数据

返回: `Wei\Validate` 验证器对象

#### validate->getData()

获取要验证的数据

返回: `array`

#### validate->setNames($names)

设置数据项的名称

返回: `Wei\Validate` 验证器对象

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

返回: `Wei\Validate`

参数

名称        | 类型         | 说明
------------|--------------|------
$field      | string       | 数据项的名称
$value      | mixed        | 数据项的值

#### validate->setMessages($messages)

设置自定义的错误信息

返回: `Wei\Validate`

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

返回: `Wei\Validator\BaseValidator`|`null` 验证器不存在时返回null

参数

名称        | 类型         | 说明
------------|--------------|------
$field      | string       | 数据项的名称
$rule       | string       | 验证规则的名称


相关链接
--------

* [验证器概览](../book/validators.md)
