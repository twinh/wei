isNull
======

检查数据是否为null

### 检查0是否为null
```php
if (wei()->isNull(0)) {
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

| 名称              | 类型    | 默认值                      | 说明       |
|-------------------|---------|-----------------------------|------------|
| notNullMesssage   | string  | %name%必须是null值          | -          |
| negativeMessage   | string  | %name%不能为null值          | -          |

### 方法

#### isNull($input)
检查数据是否为null
