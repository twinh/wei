[validate()](http://twinh.github.com/widget/api/validate)
=========================================================

检查数组或对象中每一个元素是否能通过指定规则的验证

### 
```php
\Widget\Validator validate( $options )
```

##### 参数
* $options 验证器的配置选项
   *  **data** `array|object` 待验证的数据,可以是数组或对象
   *  **rules** `array` 验证规则数组
   *  **messages** `array` 验证错误时的提示信息
   *  **names** `array` 数据项名称的数组,用于错误信息提示
   *  **ruleValid** `callback` 规则验证通过时调用的回调函数
   *  **ruleInvalid** `callback` 规则验证不通过时调用的回调函数
   *  **fieldValid** `callback` 数据项验证通过时调用的回调函数
   *  **fieldInvalid** `callback` 数据项验证不通过时调用的回调函数
   *  **success** `callback` 验证器验证通过(所有验证规则都通过)时调用的回调函数
   *  **failure** `callback` 验证器验证不通过(任意验证规则不通过)时调用的回调函数
   *  **breakRule** `bool` 是否当任意一项规则验证不通过时就中断验证流程,默认为false
   *  **breakField** `bool` 是否当任意一项数据验证不通过时就中断验证流程,默认为false
   *  **skip** `bool` 是否当任意一项数据中的一项规则不通过时,就跳转到下一项数据的验证流程,默认是false,启用后,每项数据最多会有一个未通过的验证规则


Widget验证器是参考[jQuery Validation](http://bassistance.de/jquery-plugins/jquery-plugin-validation/)
开发的数据验证器,她与`jQuery Validation`有着很多相似的地方,所以如果你使用过
`jQuery Validation`,使用Widget验证器将非常容易上手.

##### 参数详细说明

##### data
待验证的数据,可以是数组或对象

验证数据的取值的顺序如下(与`attr`微件一致)

1. 如果`$data`是数组或`\ArrayAccess`的实例化对象,检查并返回`$data[$key]`的值
2. 如果`$data`是对象,检查属性`$key`是否存在,存在则返回`$data->$key`的值
3. 如果`$data`是对象且方法`get. $key`存在,返回`$data->{'get' . $key}`
4. 如果以上均不存在,返回null

**代码范例**

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
$widget->validate(array(
    'data' => array(
        'name' => 'twin',
        'email' => 'test@test.com'
    )
));

// 以数组对象为验证数据
$widget->validate(array(
    'data' => new \ArrayObject(array(
        'name' => 'twin',
        'email' => 'test@test.com'
    ))
));

// 以对象为验证数据
$widget->validate(array(
    'data' => new User
));
```

##### rules
验证规则数组.键名是验证的数据项名称,值是验证规则.验证规则可以字符串,表示一项验证规则,也可以是数组
,表示多项验证规则.

**注意:**验证规则数组有一点与`jQuery Validation`不同,默认情况下,`jQuery Validation`的所有
数据项都是 **可选** 的,但是Widget验证器的所有数据项都是 **必选** 的.如果某一个数据项是选填的,只需增
加`required => false`的验证规则

在验证过程中,验证器会根据验证规则的名称,检查对应的规则验证器是否存在,如`email`规则将被转换为
`\Widget\Validator\Email`类,如果类不存在,将抛出`\Widget\Exception\InvalidArgumentException`
异常提醒开发人员规则不存在.如果类存在,将初始化该类.然后,验证器组织验证规则提供的参数,供规则验证器验证.

```php
$widget->validate(array(
    'rules' => array(
        // 简单规则,将会被转换为 array('required' => true)
        'name' => 'required',
        // 复合规则
        'email' => array(
            'required' => false, // 设置为false表示email是选填
            'email' => true
        ),
    )
))
```

#### messages
验证错误时的提示信息.提示信息的格式与验证规则类似.

**代码范例**

```php
$widget->validate(array(
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
    'messges' => array(
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
        )
    )
));
```

##### names
数据项名称的数组,用于错误信息提示.数组的键名是验证数据项的值,数值的值是验证数据项的名称.如

**注意:**如果未提供数据项名称,错误信息将以`该项`作为验证数据项的名称,完整的错误信息例如`该项不能为空`

**代码范例**
```php
$widget->validate(array(
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

##### ruleValid
当任意一条规则验证通过时,验证器就会触发`ruleValid`事件.

**参数**

```php
ruleValid( $event, $widget, $rule, $field, $validator )
```

* $event `Event` 事件对象
* $widget `Widget` 微件管理器
* $rule `string` 通过的规则名称
* $field `string` 当前验证的数据项名称
* $validator `Validator` 验证器对象

如果`ruleValid`事件返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

##### ruleInvalid
当任意一条规则验证 **不** 通过时,验证器就会触发`ruleInvalid`事件.

**参数**

```php
ruleInvalid( $event, $widget, $rule, $field, $validator )
``` 

* $event `Event` 事件对象
* $widget `Widget` 微件管理器
* $rule `string` 未通过的规则名称
* $field `string` 当前验证的数据项名称
* $validator `Validator` 验证器对象

`ruleInvalid`与`ruleValid`的行为一致.
同样的,如果`ruleInvalid`事件返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

##### fieldValid
当任意数据项验证通过时,验证器就会触发`fieldValid`事件.

**参数**

```php
fieldValid ( $event, $widget, $field, $validator )
```

* $event `Event` 事件对象
* $widget `Widget` 微件管理器
* $field `string` 当前验证的数据项名称
* $validator `Validator` 验证器对象

如果`fieldValid`事件返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

##### fieldInvalid
当任意数据项验证 **不** 通过时,验证器就会触发`fieldInvalid`事件.

**参数**

```php
fieldInvalid ( $event, $widget, $field, $validator )
```

* $event `Event` 事件对象
* $widget `Widget` 微件管理器
* $field `string` 当前验证的数据项名称
* $validator `Validator` 验证器对象

如果`fieldInvalid`事件返回false,验证器将直接中断后续所有验证流程,直接返回验证结果.

##### success
验证结束时,如果最终验证结果为通过,验证器就触发`success`事件.

```php
success ( $event, $widget, $validator )
```

* $event `Widge\Event\Event` 事件对象
* $widget `Widget\Widget` 微件管理器
* $validator `Widget\Validator` 验证器对象

**代码范例**

```php
$widget->validate(array(
    'data' => array(
        'name' => 'twin'
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'success' => function($event, $widget, $validator) {
        echo 'Yes';
    }
));
```

**运行结果**

```php
'Yes'
```

##### failure
验证结束时,如果最终验证结果为 **不** 通过,验证器就触发`failure`事件.

```php
failure ( $event, $widget, $validator )
```

* $event `Widge\Event\Event` 事件对象
* $widget `Widget\Widget` 微件管理器
* $validator `Widget\Validator` 验证器对象

**代码范例**

```php
$widget->validate(array(
    'data' => array(
        'name' => null
    ),
    'rules' => array(
        'name' => 'required'
    ),
    'failure' => function($event, $widget, $validator) {
        echo 'No';
    }
));
```

**运行结果**

```php
'No'
```

##### breakRule 
默认为不启用.启用该选项后,当任意一项规则验证不通过时就中断验证流程,直接返回验证结果(false)

##### breakField
默认为不启用.启用该选项后,当任意一项数据验证不通过时就中断验证流程,直接返回验证结果(false)

##### skip
默认是不启用.启用该选项后,当任意一项数据中的一项规则不通过时,就跳转到下一项数据的验证流程,因此,每项数据最多会有一个未通过的验证规则

- - - - 


##### 代码范例
检查数据是否符合指定的验证规则
```php
<?php
 
$validator = $widget->validate(array(
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
    )
));

print_r($validator->getSummaryMessages());

```
##### 运行结果
```php
'Array
(
    [username] => Array
        (
            [0] => This value must have a length greater than 3
        )

    [email] => Array
        (
            [0] => This value must be valid email address
        )

)
'
```
