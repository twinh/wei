Download
========

发送下载文件的HTTP响应

案例
----

### 下载当前运行的文件
```php
widget()->download(__FILE__);
```
#### 运行结果
![弹出下载对话框](resources/download.png)

调用方式
--------

### 选项

| 名称          | 类型   | 默认值                 | 说明|
|---------------|--------|------------------------|-----------------------------------------------------------------------------------------------------------------------------------------|
| filename      | string | null                   | 弹出下载对话框时显示的文件名称                                                                                                          |
| type          | string | application/x-download | 指定HTTP内容类型（Content-Type）                                                                                                        |
| disposition   | string | attachment             | 下载的方式,可选项为`inline`或`attachment`,如果是`inline`,浏览器会先尝试直接在浏览器中打开,如果是`attachment`,浏览器将直接弹出下载对话框 |
        

### 方法

#### download($file, $options = array())
下载指定的文件
