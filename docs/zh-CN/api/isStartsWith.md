isStartsWith
============

检查数据是否以指定字符串开头

案例
----

### 检查"abc"是否以"C"开头

```php
if (wei()->isEndsWith('abc', 'C')) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'Yes'
```

### 以区分大小写的方式,检查"abc"是否以"C"开头
```php
if (wei()->isEndsWith('abc', 'C', true)) {
    echo 'Yes';
} else {
    echo 'No';
}
```

#### 运行结果

```php
'No'
```

### 检查"abc"是否以数组array('a', 'b', 'c')中的任意元素开头

```php
if (wei()->isEndsWith('abc', array('a', 'b', 'c'))) {
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

名称   | 类型         | 默认值 | 说明
-------|--------------|--------|------
findMe | string,array | -      | 要查找的字符串.如果是数组,只要数据以数组中任何一个元素开头就算验证通过
case   | bool         | false  | 查找时是否区分大小写

### 错误信息

名称                       | 信息
---------------------------|------
notStringMessage           | %name%必须是字符串
notFoundMessage            | %name%必须以%findMe%开头
negativeMessage            | %name%不能以%findMe%开头

### 方法

#### isStartsWith($input, $findMe, $case = false)
检查数据是否以指定字符串开头

相关链接
--------

* [验证器概览](../book/validators.md)
* [检查数据是否以指定字符串结尾:isEndsWith](isEndsWith.md)