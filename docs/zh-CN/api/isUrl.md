isUrl
=====
检查数据是否为有效的URL地址,可选的检查选项有"path"和"query"

案例
----

### 检查`http://www.example.com`是否为有效的URL地址
```php
if (wei()->isUrl('http://www.example.com')) {
    echo 'Yes';
} else {
    echo 'No';
}
// 输出了Yes
```

### 检查`http://www.example.com`是否为有效的URL地址,要求带有查询参数
```php
if (wei()->isUrl('http://www.example.com', array('query' => true))) {
    echo 'Yes';
} else {
    echo 'No';
}
// 输出了No
```

调用方式
--------

### 选项

名称              | 类型    | 默认值                    | 说明
------------------|---------|---------------------------|------
path              | bool    | false                     | 是否要求URL带有路径,如http://www.example.com/path/part
query             | bool    | false                     | 是否要求URL带有查询参数,如http://www.example/?query=string
notStringMessage  | string  | %name%必须是字符串        | -
invalidMessage    | string  | %name%必须是有效的URL地址 | -
negativeMessage   | string  | %name%不能是URL地址       | -

### 方法

#### isUrl($input, $options = array())
检查数据是否为有效的URL地址

相关链接
--------

* [验证器概览](../book/validators.md)