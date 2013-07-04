isMax
=====

检查数据是否小于等于指定的值

案例
----

### 检查10是否小于等于20
```php
if (widget()->isMax(10, 20)) {
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

| 名称              | 类型    | 默认值                             | 说明                 |
|-------------------|---------|------------------------------------|----------------------|
| max               | int     | 无                                 | 待比较的数值         |
| notStringMessage  | string  | %name%必须是字符串                 | -                    |
| maxMessage        | string  | %name%必须小于等于%max%            | -                    |
| negativeMessage   | string  | %name%必须不小于等于%max%          | -                    |

### 方法

#### isMax($input, $max)
检查数据是否小于等于指定的值
