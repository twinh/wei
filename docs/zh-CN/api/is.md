->is()
=====

目录
* ->is($rule, $data, $options)
* ->is((function($input, Widget\Validator\Callback $fn, Widget\Widget $widget), $data))
* ->is($rules, $input)

- - - 

### 检查数据是否能通过指定规则的验证
```php
bool is($rule, $input, $options)
```
返回是否通过验证

#### 参数
* $rule      `string`  验证器的名称
* $input      `mixed`   待验证的数据 
* $options   `array`   验证器的配置选项

#### 范例
验证数据是否为邮箱
```php
if ($widget->is('email', 'twinhuang@qq.com')) {
       echo 'twihuang@qq.com是邮箱';
} else {
       echo 'twihuang@qq.com不是邮箱'
}
```

#### 输出
```php
`twihuang@qq.com是邮箱`
```

- - -

### 检查数据是否能通过指定闭包的验证
```php
bool is((function($input, Widget\Validator\Callback $fn, Widget\Widget $widget), $input))
```
返回是否通过验证

#### 参数
* function($input, Widget\Validator\Callback $fn, Widget\Widget $widget) 用于验证数据的闭包
* $input 待验证的数据

#### 范例
```php
if ($widget->is(3, function($input) {
    return 0 === 10 % $input;
}) {
    echo 'success';
} else {
    echo 'failure';
}
```

#### 输出
```php
'failure'
```
