[validate()](http://twinh.github.com/widget/api/validate)
=========================================================

检查数组或对象中每一个元素是否能通过指定规则的验证

### 
```php
\Widget\Validator validate( $options )
```

##### 参数
* $options 验证器的配置选项
   *  **data** `array|object` 待验证的数组或对象
   *  **rules** `array` 验证规则数组
   *  **messages** `array` 验证错误时的提示信心
   *  **ruleValid** `callback` 规则验证通过时调用的回调函数
   *  **ruleInvalid** `callback` 规则验证不通过时调用的回调函数
   *  **fieldValid** `callback` 数据项验证通过时调用的回调函数
   *  **fieldInvalid** `callback` 数据项验证不通过时调用的回调函数
   *  **success** `callback` 验证器验证通过(所有验证规则都通过)时调用的回调函数
   *  **failure** `callback` 验证器验证不通过(任意验证规则不通过)时调用的回调函数
   *  **breakField** `bool` 是否当任意一项数据验证不通过时就中断验证流程
   *  **breakRule** `bool` 是否当任意一项规则验证不通过时就中断验证流程
   *  **skip** `bool` 是否当任意一项数据中的一项规则不通过时,就跳转到下一项数据的验证流程,默认是false,启用后,每项数据最多会有一个未通过的验证规则

##### 范例
检查数据提交的数据是否符合验证规则
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
##### 输出
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
