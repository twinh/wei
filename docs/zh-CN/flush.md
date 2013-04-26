flush
=====

直接输出内容到浏览器

代码范例
--------

### 逐个打印1-10的数字到浏览器窗口,没每隔一秒输出一个
```php
//
$widget->flush();

for ($i = 1; $i <= 10; $i++) {
    echo $i;
    sleep(1);
}
```

调用方式
--------

### 选项

*无*

### 方法

#### flush($content = null, $status = null)
直接输出内容到浏览器
