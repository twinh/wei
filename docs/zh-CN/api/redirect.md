[redirect()](http://twinh.github.com/widget/api/redirect)
=========================================================

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

