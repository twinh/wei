->is()
=====

检查数据是否能通过指定规则的验证
```php
->is($rule, $data, $options)
```
* $rule      `string`  验证器的名称
* $data      `mixed`   待验证的数据 
* $options   `array`   验证器的配置选项

返回: `bool` 是否通过验证

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
