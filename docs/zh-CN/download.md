Download
========

发送下载文件的HTTP响应

案例
----

### 下载当前运行的文件
```php
$widget->download(__FILE__);
```
#### 运行结果
![弹出下载对话框](resources/download.png)

调用方式
--------

### 选项
| 名称 | 类型 | 默认值 | 说明|
|------|------|--------|-----|
| file | string | null | 要下载的文件路径
| type | string | application/x-download | 指定HTTP内容类型（Content-Type）|


### 方法

#### download($file, $options = array())
下载指定的文件
