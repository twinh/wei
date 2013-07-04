Arr
===

数组工具微件,提供一些实用的数组操作方法

案例
----

### 获取数组或对象中指定键名的值
```php
class Getter
{
    public function getKey()
    {
        return 'Get from Getter';
    }
}

$data = array(
    array(
        'key' => 'Get from array',
    ),
    new \ArrayObject(array(
        'key' => 'Get from ArrayObject'
    )),
    call_user_func(function(){
        $object = new stdClass();
        $object->key = 'Get from stdClass';
        return $object;
    }),
    new Getter()
);

foreach ($data as $row) {
    echo widget()->arr->attr($row, 'key') . "\n";
}
```
### 运行结果
```php
'Get from array'

'Get from ArrayObject'

'Get from stdClass'

'Get from Getter'
```

### 对二维数组进行按order键名,从大到小排序
```php
$data = array(
    array('id' => 2, 'order' => 1),
    array('id' => 1, 'order' => 2)
);

print_r(widget()->arr->sort($data, 'order', SORT_DESC));
```

### 运行结果
```php
Array
(
    [0] => Array
        (
            [id] => 1
            [order] => 2
        )
    [1] => Array
        (
            [id] => 2
            [order] => 1
        )
)
```

调用方法
--------

### 选项

*无*

### 方法

#### arr->attr($data, $key)
获取数组或对象中指定键名的值

取值的顺序如下

1. 如果`$data`是数组或`\ArrayAccess`的实例化对象,检查并返回`$data[$key]`的值
2. 如果`$data`是对象,检查属性`$key`是否存在,存在则返回`$data->$key`的值
3. 如果`$data`是对象且方法`get. $key`存在,返回`$data->{'get' . $key}`
4. 如果以上均不存在,返回null

使用`attr()`微件的好处在于你不用关心要取值的变量类型,不管它是数组,对象数组(\ArrayAccess),还是
其他对象

#### arr->sort($array, $key = 'order', $type = SORT_ASC)
对一个二维数组进行排序,类似SQL的ORDER BY语句
