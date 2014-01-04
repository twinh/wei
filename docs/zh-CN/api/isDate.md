isDate
======

检查数据是否为合法的日期

案例
----

### 检查"2013-01-01"是否为合法的日期

```php
if (wei()->isDate('2013-01-01')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'Yes'
```

### 检查"2046-01-01"是否在今天之前(不包含今天)

```php
if (wei()->isDate('2046-01-01', array('before' => date('Y-m-d')))) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'No'
```

### 检查"2046-01-01"是否在今天之后(不包含今天)

```php
if (wei()->isDate('2046-01-01', array('after' => date('Y-m-d')))) {
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

名称    | 类型    | 默认值  | 说明
--------|---------|---------|------
format  | string  | Y-m-d   | 用于匹配数据的日期格式
before  | string  | 无      | 输入的日期应该早于`before`的值
after   | string  | 无      | 输入的日期应该晚于`after`的值

完整的日期格式可以查看PHP官方文档中关于[date](http://php.net/manual/zh/function.date.php)函数的格式说明.

### 错误信息

名称                   | 信息
-----------------------|------
notStringMessage       | %name%必须是字符串
invalidMessage         | %name%必须是合法的日期时间(当日期无法解析时出现)
formatMessage          | %name%不是合法的日期,格式应该是%format%,例如:%example%
tooEarlyMessage        | %name%必须晚于%after%
tooLateMessage         | %name%必须早于%before%
negativeMessage        | %name%不能是合法的日期

### 方法

#### isDate($input, $format = 'Y-m-d')

检查数据是否为合法的日期

#### isDate($input, $options = array())

检查数据是否为合法的日期,第二个参数为选项数组

相关链接
--------

* [验证器概览](../book/validators.md)
* [检查数据是否为合法的日期时间:isDateTime](isDateTime.md)
* [检查数据是否为合法的时间:isTime](isTime.md)