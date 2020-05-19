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
// abc
array(
    'lengthTooShort' => '密码的长度必须大于等于6'
);

// abcdef
array(
    'missingCharType' => '密码必须包含数字(0-9)'
);

// 123456
array(
    'missingCharType' => '密码必须包含字母(a-z)'
);

// abc123
'Yes';
```

### 推荐的选项组合

* 密码最少由6个字符组成,必须包含数字和字母
    
    ```php
    wei()->isPassword($input, array(
        'minLength' => 6,
        'needDigit' => true,
        'needLetter' => true,
    ));
    ```

* 密码最少由6个字符组成,必须包含`数字`,`小写字母`,`大写字母`和`非数字字母的其他字符`中的3种
    
    ```php
    wei()->isPassword($input, array(
        'minLength' => 6,
        'atLeastPresent' => 3,
    ));
    ```

调用方式
--------

### 选项

名称           | 类型    | 默认值  | 说明
---------------|---------|---------|------
minLength      | int     | 无      | 密码的最小长度,常用`6`和`8`
maxLength      | int     | 无      | 密码的最大长度
needDigit      | bool    | false   | 密码是否必须包含数字
needLetter     | bool    | false   | 密码是否必须包含字母
needNonAlnum   | bool    | false   | 密码是否必须包含非数字字母的其他字符,如!@#
atLeastPresent | int     | 0       | 密码必须包含`数字`,`小写字母`,`大写字母`和`非数字字母的其他字符`中的几种

### 错误信息

名称                   | 信息
-----------------------|------
lengthTooShortMessage  | %name%的长度必须大于等于%minLength%
lengthTooLongMessage   | %name%的长度必须小于等于%maxLength%
missingCharTypeMessage | %name%必须包含%missingType%
missingChar            | %name%必须包含%missingType%中的%missingCount%种

### 方法

#### isPassword($input)
检查组成密码的字符是否符合要求格式

相关链接
--------

* [验证器概览](../book/validators.md)