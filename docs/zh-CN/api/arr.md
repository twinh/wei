Arr
===

数组工具类,提供一些实用的数组操作方法

案例
----

### 对二维数组进行按order键名,从大到小排序
```php
$data = array(
    array('id' => 2, 'order' => 1),
    array('id' => 1, 'order' => 2)
);

print_r(wei()->arr->sort($data, 'order', SORT_DESC));
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

#### arr->sort($array, $key = 'order', $type = SORT_ASC)
对一个二维数组进行排序,类似SQL的ORDER BY语句
