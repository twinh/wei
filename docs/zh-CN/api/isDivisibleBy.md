isDivisibleBy
=============

检查数据是否能被指定的除数整除

案例
----

### 检查10能否被3整除
```php
if (wei()->isDivisibleBy(10, 3)) {
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

| 名称                | 类型    | 默认值                           | 说明              |
|---------------------|---------|----------------------------------|-------------------|
| notStringMessage    | string  | %name%必须是字符串               | -                 |
| notDivisibleMessage | string  | %name%必须被%divisor%整除        | -                 |
| negativeMessage     | string  | %name%不可以被%divisor%整除      | -                 |

### 方法

#### isDivisibleBy($input)
检查数据是否能被指定的除数整除
