Password
========

安全简单的密码加密,校验服务

基于[ircmaxell/password_compat](https://github.com/ircmaxell/password_compat)

案例
----

### 保存注册用户的密码

```php
// 获取表单提交的密码字符串
$password = wei()->request('password');

// 生成在加密(散列)密码用的盐(干扰字符串)
$salt = wei()->password->generateSalt();

// 通过密码和盐加密密码
$password = wei()->password->hash($password, $salt);

// 构造用户信息数组,注意保存盐供后续使用
$user = array(
    'username' => 'xxx',
    'salt' => $salt,
    'password' => $password,
);

// 保存到数据库
wei()->db->insert('user', $user);
```

### 加密字符串

如果盐为空将自动生成

```php
$password = wei()->password->hash('12@Wer@34');

// 输出结果为60字节的字符串,类似如下
'$2y$10$fY5aEqo6uzXme2.6jIfFnei.J95RVOwZBKR3ueAkBIVCgpP2XbAga'
```

### 校验密码是否正确

```php
// 密码,一般从前台表单中获取
$password = '12@Wer@34';
// 加密过的密码,一般从数据库中取出来
$hash = '$2y$10$fY5aEqo6uzXme2.6jIfFnei.J95RVOwZBKR3ueAkBIVCgpP2XbAga';

if (wei()->password->verify($password, $hash)) {
    // 通过session设置用户登录态
    wei()->session['user'] = 'xxx';
    echo '密码正确';
} else {
    echo '密码错误';
}
```

调用方式
--------

### 选项

名称      | 类型      | 默认值  | 说明
----------|-----------|---------|------
cost      | int       | 10      | 加密算法递归的层数,应该在4~32之间

### 方法

#### password->hash($password, $salt = null)
加密密码

返回: `string` 60个字符长度的字符串

#### password->verify($password, $hash)
检查密码是否正确

返回: `boolean`

#### password->generateSalt()
生成适合密码加密的盐(干扰字符串)

返回: `string` 22个字符长度的字符串

#### password->getInfo($hash)
获取加密密码的加密信息

返回: `array`

相关链接
--------

* PHP密码加密相关函数 http://php.net/password
* 兼容PHP5.5以下的密码加密函数 https://github.com/ircmaxell/password_compat