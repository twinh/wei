isIdenticalTo
=============

检查数据是否完全等于(===)指定的值

案例
----

### 检查指定的两个值是否完全相等

```php
$post = array(
    'password' => '123456',
    'password_confirmation' => '123456',
);

if (wei()->isIdenticalTo($post['password'], $post['password_confirmation'])) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'Yes'
```

### 检查`0`和`false`是否完全相等

```php
if (wei()->isIdenticalTo(0, false)) {
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

名称              | 类型    | 默认值                   | 说明
------------------|---------|--------------------------|------
value             | mixed   | 无                       | 与数据比较的值

### 错误信息

名称                       | 信息
---------------------------|------
invalidMessage             | %name%必须等于%value%
negativeMessage            | %name%不能等于%value%

### 方法

#### isIdenticalTo($input, $value)

检查数据是否完全等于(===)指定的值

相关链接
--------

* [验证器概览](../book/validators.md)