isIp
====

检查数据是否为有效的IP地址

案例
----

### 检查"192.168.0.1"是否为有效的IP地址
```php
if (wei()->isIp('192.168.0.1')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'Yes'
```

### 检查"192.168.0.1"是否不在私有的IP地址中
```php
if (wei()->isIp('192.168.0.1', array('noPrivRange' => true))) {
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

| 名称              | 类型    | 默认值                           | 说明                       |
|-------------------|---------|----------------------------------|----------------------------|
| ipv4              | bool    | false                            | 是否只允许IPv4格式的IP地址 |
| ipv6              | bool    | false                            | 是否只允许IPv6格式的IP地址 |
| noPrivRange       | bool    | false                            | 是否不允许私有的IP地址     |
| noResRange        | bool    | false                            | 是否不允许保留的IP地址     |
| notAllowMessage   | string  | %name%必须是有效的IP地址         | -                          |
| negativeMessage   | string  | %name%不能是IP地址               | -                          |

### 方法

#### isIp($input, $options = array())
检查数据是否为有效的IP地址

相关链接
--------

* [验证器概览](../book/validators.md)