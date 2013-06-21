isCreditCard
============

检查数据是否为合法的信用卡号码

案例
----

### 检查数据是否为Visa或银联卡,第二个参数即可以是字符串,也可以是数组
```php
$input = '4111111111111111'; // Visa

if (widget()->isCreditCard($input, 'UnionPay,Visa')) {
    echo 'Yes';
} else {
    echo 'No';
}

if (widget()->isCreditCard($input, array('UnionPay', 'Visa'))) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果
```php
'Yes'
'Yes'
```

调用方式
--------

### 选项

| 名称              | 类型          | 默认值                     | 说明                                                                 |
|-------------------|---------------|----------------------------|----------------------------------------------------------------------|
| type              | string,array  | 无                         | 指定信用卡类型,多个使用`,`隔开,或是使用数组,留空表示允许任意信用卡号 |
| notStringMessage  | string        | %name%必须是字符串         | -                                                                    |
| invalidMessage    | string        | %name%必须是有效的信用卡号 | -                                                                    |
| negativeMessage   | string        | %name%不能是有效的信用卡号 | -                                                                    |

允许指定的信用卡类型有: American Express, Diners Club, Discover, JCB, MasterCard, China UnionPay 和 Visa

下表为目前允许的信用卡类型

| **发卡机构**     | **中文名称** | **值**       |
|------------------|--------------|--------------|
| American Express | 美国运通     | `Amex`       |
| Diners Club      | 大来卡       | `DinersClub` |
| Discover Card    | 发现卡       | `Discover`   |
| JCB              | -            |`JCB`         |
| MasterCard       | 万事达卡     | `MasterCard` |
| China UnionPay   | 中国银联     | `UnionPay`   |
| Visa             | -            | `Visa`       |

### 方法

#### isCreditCard($input, $type)
