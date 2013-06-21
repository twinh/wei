isMin
=====

检查数据是否大于等于指定的值

案例
----

### 检查10是否大于等于20
```php
if (widget()->isMin(10, 20)) {
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

| 名称              | 类型    | 默认值                             | 说明                 |
|-------------------|---------|------------------------------------|----------------------|
| min               | int     | 无                                 | 待比较的数值         |
| notStringMessage  | string  | %name%必须是字符串                 | -                    |
| maxMessage        | string  | %name%必须大于等于%min%            | -                    |
| negativeMessage   | string  | %name%不合法                       | -                    |

### 方法

#### isMin($input, $min)
检查数据是否大于等于指定的值
