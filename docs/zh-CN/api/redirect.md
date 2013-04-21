[redirect()](http://twinh.github.io/widget/api/redirect)
========================================================

发送页面跳转的HTTP响应

### 
```php
\Widget\Redirect redirect( $url [, $status [, $options ] ] )
```

##### 参数
* **$url** `string` 跳转到的Url地址
* **$status** `int` 响应的HTTP状态码,默认为`302`
* **$options** `array` 跳转微件的配置选项
   *  **delay** `int` 延迟跳转的秒数,默认是0
   *  **view** `string` 自定义视图文件的路径


**注意**: 如果delay为0,使用的是HTTP header跳转,如果不为0,因为HTTP header不支持延迟跳转,所以使用的是HTML [Meta refresh](http://en.wikipedia.org/wiki/Meta_refresh)跳转,此时,可以通过view选项指定更加友好的跳转视图.

