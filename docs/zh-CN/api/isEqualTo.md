isEquals
========

检查数据是否与指定数据相等

案例
----

### 检查指定的两个值是否相等
```php
$post = array(
    'password' => '123456',
    'password_confirmation' => '123456',
);

if (widget()->isEquals($post['password'], $post['password_confirmation'])) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'Yes'
```

调用方式
--------

### 选项

| 名称              | 类型    | 默认值                   | 说明                                             |
|-------------------|---------|--------------------------|--------------------------------------------------|
| equals            | mixed   | 无                       | 与数据比较的值                                   |
| strict            | int     | 无                       | 是否使用全等(===)进行比较,默认使用等于(==)比较   |
| notEqualsMessage  | string  | %name%必须等于%equals%   | -                                                |
| negativeMessage   | string  | %name%不能等于%equals%   | -                                                |

### 方法

#### isEquals($input, $equals, $strict = false)
检查数据是否与指定数据相等
