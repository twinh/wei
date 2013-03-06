[isCallback](http://twinh.github.com/widget/api/isCallback/)
============
检查数据是否通过指定回调方法验证

```php
bool isCallback( $input [, $fn [, $message ]] )
```
**说明:** 检查数据是否通过指定回调方法验证  
* $input `mixed` 待验证的数据
* $fn `callback` 指定验证的回调结构
* $message `string` 验证不通过时返回的信息

##### 范例
通过回调函数检查数据是否能被3整数
```php
if ($widget->is(10, function($input) {
    return 0 === $input % 3;
}) {
    echo 'success';
} else {
    echo 'failure';
}
```

##### 输出
```php
'failure'
```
