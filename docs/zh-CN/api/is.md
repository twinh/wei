is
==

验证管理器,用于检查数据是否能通过指定规则,或指定回调结构的验证

案例
----

### 检查数据是否为邮箱
```php
if (widget()->is('email', 'twinhuang@qq.com')) {
    echo 'twihuang@qq.com is valid email';
} else {
    echo 'twihuang@qq.com is not valid email';
}
```

#### 运行结果
```php
'twihuang@qq.com is valid email'
```

### 检查数据是否能被3整除
```php
$fn = function($input) {
    return 0 === $input % 3;
};

if (widget()->is($fn, 10)) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'No'
```

### 检查数据是否为数字,且长度在3到5之间
```php
$rules = array(
    'digit' => true,
    'length' => array(3, 5)
);
if (widget()->is($rules, '123456')) {
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

### 方法

#### is($rule, $input, $options = array())
检查数据是否能通过指定规则的验证

名称       | 类型     | 默认值   | 说明
-----------|----------|----------|------
$rule      | string   | 无       | 验证器的名称
$input     | mixed    | 无       | 待验证的数据
$options   | array    | array()  | 验证器的配置选项

#### is($fn, $input)
检查数据是否能通过指定回调结构的验证

名称       | 类型     | 默认值   | 说明
-----------|----------|----------|------
$fn        | callback | 无       | 用于验证数据的回调结构
$input     | mixed    | 无       | 待验证的数据配置选项

回调结构`$fn`包含三个参数,分别是

名称       | 类型                       | 默认值   | 说明
-----------|----------------------------|----------|------
$input     | mixed                      | 无       | 待验证的数据
$callback  | \Widget\Validator\Callback | 无       | 回调验证器对象
widget()    | \Widget\Widget             | 无       | 微件管理器


#### is($rules, $input)
检查数据是否能通过指定的验证规则数组验证

名称       | 类型     | 默认值   | 说明
-----------|----------|----------|------
$rules     | array    | 无       | 证规则数组,数组的键名是规则名称,数组的值是验证规则的配置选项
$input     | mixed    | 无       | 待验证的数据配置选项

#### is->hasRule($rule)
检查是否存在该验证规则

#### is->createRuleValidator($rule, array $options = array())
创建一个规则验证器对象
