Json
====

输出JSON或JSONP格式的数据到浏览器

代码范例
--------

### 输出JSON格式的数据
```php
// 输出 {"code":1,"message":"success"}
widget()->json('success', 1);
```

### 输出JSONP格式的数据
```php
// 假设URL请求地址为 index.php?callback=callback
// 下面的调用将输出 callback({"code":1,"message":"success"})
widget()->json('success', 1, array(), true);
```

调用方式
--------

### 选项

*无*

### 方法

#### json($message, $code = 1, $append = array(), $jsonp = false)

参数

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
$message  | string    | 无        | 返回message的值
$code     | int       | 1         | 返回code的值
$append   | array     | array()   | 附加的数据
$jsonp    | bool      | false     | 是否允许返回JSONP格式的数据
