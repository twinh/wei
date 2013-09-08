Response
========

发送HTTP响应头和响应内容

案例
----

### 发送内容为"Hello World"的HTTP响应

```php
widget()->response('Hello World');
```

#### 运行结果

```php
'Hello World'
```

#### 获取上面案例中HTTP响应的完整内容

```php
echo (string)widget()->response;
```
#### 运行结果

```php
'HTTP/1.1 200 OK

Hello World'
```

### 下载指定的文件

```php
widget()->download(__FILE__);
```

#### 运行结果

![弹出下载对话框](resources/download.png)

调用方式
--------

### 选项

名称           | 类型      | 默认值    | 说明
---------------|-----------|-----------|------
version        | string    | 1.1       | HTTP版本
statusCode     | int       | 200       | HTTP状态码
statusText     | string    | OK        | HTTP状态消息
content        | string    | 无        | HTTP响应内容
isSent         | bool      | false     | HTTP响应内容是否已发送
cookieOption   | array     | -         | 详见下表
downloadOption | array     | -         | 详见下表


#### `cookieOption`默认选项

名称      | 类型      | 默认值    | 说明
----------|-----------|-----------|------
expires   | int       | 86400     | cookie相对于现在的过期秒数
path      | string    | /         | cookie活动的路径
domain    | string    | null      | 保存该cookie的域名
secure    | bool      | false     | 是否只通过HTTPS安全连接来发送,只有在HTTPS连接下才有效
httpOnly  | bool      | false     | 是否只通过HTTP协议发送cookie,如果是,客户端javascript将无法读取到该cookie
raw       | bool      | false     | 是否发送为不经过URL解码的cookie

#### `downloadOption`默认选项

名称          | 类型   | 默认值                 | 说明
--------------|--------|------------------------|------
filename      | string | null                   | 弹出下载对话框时显示的文件名称
type          | string | application/x-download | 指定HTTP内容类型（Content-Type）
disposition   | string | attachment             | 下载的方式,可选项为`inline`或`attachment`,如果是`inline`,浏览器会先尝试直接在浏览器中打开,如果是`attachment`,浏览器将直接弹出下载对话框

### 方法

#### response($content, $status = null)
发送HTTP响应头和响应内容

名称          | 类型      | 默认值    | 说明
--------------|-----------|-----------|------
$content      | string    | 无        | HTTP响应的内容
$stateCode    | int       | 200       | HTTP响应的状态码

#### response->setContent($content)
设置HTTP响应的内容

#### response->getContent()
获取HTTP响应的内容

#### response->setStatusCode($code, $text = null)
设置HTTP响应的状态码

#### response->setStatusCode()
获取HTTP响应的状态码

#### response->isSent()
检查内容是否已发送

#### response->setSentStatus($bool)
设置内容是否已发送

#### response->download($file, $downloadOptions = array())
下载指定的文件
