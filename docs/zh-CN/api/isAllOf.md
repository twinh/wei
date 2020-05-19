isAllOf
=======

检查数据是否通过所有的规则校验

案例
----

### 检查数据是否为5-10位的数字

```php
$input = '123456';
if (wei()->isAllOf($input, array(
    'length' => array(5, 10),
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

名称                | 类型    | 默认值  | 说明
--------------------|---------|---------|------
rules               | array   | -       | 验证规则数组,数组的键名是规则名称,数组的值是验证规则的配置选项

### 错误信息

名称                   | 信息
-----------------------|------
atLeastMessage         | %name%必须满足以下所有规则

### 方法

#### isAllOf($input, $rules)
检查数据是否通过所有的规则校验

相关链接
--------

* [验证器概览](../book/validators.md)
* [区分all和allOf验证规则](validate.md#%E6%A1%88%E4%BE%8B%E5%8C%BA%E5%88%86all%E5%92%8Callof%E9%AA%8C%E8%AF%81%E8%A7%84%E5%88%99)
