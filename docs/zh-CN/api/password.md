Password
========

安全简单的密码加密,校验服务

代码范例
--------

### 加密密码

```php
$password = wei()->password->hash('12@Wer@34');

// 输出结果为60字节的字符串,类似如下
'$2y$10$fY5aEqo6uzXme2.6jIfFnei.J95RVOwZBKR3ueAkBIVCgpP2XbAga'
```

### 校验密码是否正确

```
// 密码,一般从前台表单中获取
$password = '12@Wer@34';
// 加密过的密码,一般从数据库中取出来
$hash = '$2y$10$fY5aEqo6uzXme2.6jIfFnei.J95RVOwZBKR3ueAkBIVCgpP2XbAga';

if (wei()->password->verify($password, $hash)) {
    echo '密码正确';
    // 通过session设置用户登录态
    wei()->session['user'] = 'xxx';
} else {
    echo '密码错误';
}
```

调用方式
--------

### 选项

名称      | 类型      | 默认值  | 说明
----------|-----------|---------|------
cost      | int       | 10      | 加密密码所消耗的时间,应该在4~32之间

### 方法

#### password->hash($password, $salt = null)
加密密码

#### password->verify($password, $hash)
检查密码是否正确

#### password->generateSalt()
生成适合密码加密的盐

#### password->getInfo($hash)
获取加密密码的加密信息