isAll
=====

检查数组里的每一项是否符合指定的规则

案例
----

### 检查数组里的每一项是否都为数字

```php
$input = array(3, 2, 5);
if (wei()->isAll($input, array(
    'digit' => true
))) {
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

名称   | 类型    | 默认值  | 说明
-------|---------|---------|------
rules  | array   | -       | 验证规则数组,数组的键名是规则名称,数组的值是验证规则的配置选项

### 错误信息

名称                   | 信息
-----------------------|------
notArrayMessage        | %name%必须是数组

### 方法

#### isAll($input, $rules)
检查数组里的每一项是否符合指定的规则

相关链接
--------

* [验证器概览](../book/validators.md)
* [区分all和allOf验证规则](validate.md#%E6%A1%88%E4%BE%8B%E5%8C%BA%E5%88%86all%E5%92%8Callof%E9%AA%8C%E8%AF%81%E8%A7%84%E5%88%99)