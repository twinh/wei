[download()](http://twinh.github.com/widget/api/download)
=========================================================

发送下载文件的HTTP响应

### 
```php
\Widget\Download download( $file [, $options ] )
```

##### 参数
* **$file** `string` 要下载的文件路径
* **$options** `array` 下载微件的配置选项
   *  **type** `string` 指定HTTP内容类型（Content-Type）,默认为`application/x-download`


##### 代码范例
下载当前运行的文件
```php
<?php

$widget->download(__FILE__);
```
##### 运行结果
![弹出下载对话框](resources/download.png)

