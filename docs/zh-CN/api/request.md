Request
=======

管理HTTP请求数据($_REQUEST)

案例
----

### 获取HTTP请求参数的值

```php
// 假设 $_REQUEST['id'] = 5;

// 返回5
$id = wei()->request('id');
```

### 获取当前请求的URL地址

```php
$url = wei()->request->getUrl();
```

### 检查当前请求是否由Ajax发起

```php
if (wei()->request->inAjax()) {
	// do something
}
```

### 获取服务器IP地址

```php
// 假设 $_SERVER['SERVER_ADDR'] = '127.0.0.1';

// 返回127.0.0.1
$ip = wei()->request->getServer('SERVER_ADDR');
```

调用方式
--------

### 选项

名称          | 类型      | 默认值    | 说明
--------------|-----------|-----------|------
fromGlobal    | bool      | true      | 是否将PHP全局变量作为请求参数,设置为`false`的话,可以自行构造请求数据
gets          | array     | array()   | 当`fromGlobal`为true时,相当于`$_GET`,否则为空
posts         | array     | array()   | 当`fromGlobal`为true时,相当于`$_POST`,否则为空
cookies       | array     | array()   | 当`fromGlobal`为true时,相当于`$_COOKIE`,否则为空
servers       | array     | array()   | 当`fromGlobal`为true时,相当于`$_SERVER`,否则为空
files         | array     | array()   | 当`fromGlobal`为true时,相当于`$_FILES`,否则为空
data          | array     | array()   | 当`fromGlobal`为true时,相当于`$_REQUEST`,否则为空
method        | string    | null      | HTTP请求方式

### 方法

#### request($name, $default = null)
获取HTTP请求参数的字符串值

#### request->getRaw($name, $default = null)
获取HTTP请求参数的原始值

#### request->getScheme()
获取URL scheme的值,返回结果为`http`或`https`

#### request->getHost()
获取主机的名称,通过域名访问则返回域名地址,通过IP地址访问则返回IP地址

#### request->getRequestUri()
获取请求的URI地址

#### request->getBaseUrl()
获取基本URL地址

#### request->getPathInfo()
获取请求的路径信息,一般用于路由器解析

#### request->getUrl()
获取用户访问的完整URL地址

#### request->getIp()
获取访问用户的IP地址

#### request->getMethod()
获取HTTP请求方式,默认为`GET`

#### request->setMethod($method)
设置HTTP请求方式,可以通过该接口设置请求为`PUT`,`DELETE`等等

#### request->isMethod($method)
检查HTTP请求方式是否为$method

#### request->isGet()
检查HTTP请求方式是否为`GET`

#### request->isPost()
检查HTTP请求方式是否为`POST`

#### request->isAjax()
检查当前是否为ajax(XMLHttpRequest)请求
