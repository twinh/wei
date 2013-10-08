isPassword
==========

检查组成密码的字符是否符合要求格式

案例
----

### 检查密码是否最少包含6个字符,由数字和字母组成

```php
$passwords = array(
    'abc',
    'abcdef',
    '123456',
    'abc123'
);

foreach ($passwords as $password) {
    $result = wei()->isPassword($password, array(
        'minLength' => 6,
        'needDigit' => true,
        'needLetter' => true,
    ));
    if ($result) {
        echo 'Yes';
    } else {
        print_r(wei()->isPassword->getMessages());
    }
}
```

#### 输出结果

```php
Array
(
    [lengthTooShort] =&gt; 密码的长度必须大于等于6
)
Array
(
    [missingCharType] =&gt; 密码必须包含数字(0-9)
)
Array
(
    [missingCharType] =&gt; 密码必须包含字母(a-z)
)
Yes
```

调用方式
--------

### 选项

### 方法

#### isPassword($input)
