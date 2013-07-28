Call
====

象jQuery Ajax一样调用您的接口

案例
----

### 一个完整的例子:获取公开的gists列表

```php
widget()->call(array(
    // 设置请求的URL地址
    'url' => 'https://api.github.com/gists/public',
    // 默认请求方式为GET,可以设置为POST,PUT等
    'method' => 'GET',
    // 自动解析返回数据为JSON数组
    'dataType' => 'json',
    // 设置发送的参数
    'data' => array(
        'time' => time(),
    ),
    'beforeSent' => function($call, $ch) {
        // 在发送前额外设置cURL选项
        curl_setopt($ch, 'xx', 'xx');
    },
    'success' => function($data, $call) {
        // 输出第一条的地址,如https://api.github.com/gists/xxxxxxx
        echo $data[0]->url;
    },
    'error' => function($call, $status, $exception){
        // 输出错误的类型,如`curl`,完全错误类型请查看选项
        echo $status;

        // 输出完整的错误信息,如`Couldn't resolve host '404.php.net'`
        echo $exception->getMessage();
    }
));
```

### 指定请求域名的IP地址:通过指向不同的IP地址,访问不同的环境

```php
widget()->call(array(
    'url' => 'http://google.com',
    'ip' => '74.125.128.105', // 线上运营环境
    //'ip' => '127.0.0.1', // 本地开发环境
    'success' => function($data){
        // do something
    }
));
```

### 通过切换IP地址实现负载均衡

```php
widget()->call(array(
    'url' => 'http://google.com',
    // 随机使用其中一个IP地址
    'ip' => array_rand(array(
        '74.125.128.147',
        '74.125.128.105'
    )),
    'success' => function($data){
        // do something
    }
));
```

### 自动设置请求地址为referer地址

```php
widget()->call(array(
    'url' => 'http://google.com',
    'referer' => true
));

// referer等于true时,相当于`url`的值

widget()->call(array(
    'url' => 'http://google.com',
    'referer' => 'http://google.com',
));
```

### 通过HTTP方法发送请求

```php
// 此处的`get`还可以是`post`, `delete`, `put`或`patch`,表示通过相应的HTTP方法发送请求
widget()->get('http://example.com', function($data, $call){
    // 成功时打印出服务器返回的结果
    print_r($data);
});
```

调用方式
--------

### 选项

名称        | 类型         | 默认值  | 说明
------------|--------------|---------|------
method      | string       | GET     | HTTP的请求方式,可以是`GET`, `POST`, `DELETE`, `PUT`, `PATCH`或任意其他服务器支持的方式
contentType | string       | application/x-www-form-urlencoded; charset=UTF-8 | HTTP请求头内容类型
cookies     | array        | array() | cookie数组,key是cookie的名称,value是cookie的值,cookie的值只能是字符串
data        | array,string | array() | 要发送到服务器的数据
gloabl      | bool         | true    | 是否使用全局配置选项
headers     | array        | array   | 要发送的HTTP头
ip          | string       | 无      | 要请求的URL地址中域名的IP地址,注意不是您的服务器IP地址
timeout     | int          | 0       | 整个请求的最大运行时间,单位是毫秒,默认是无限制
dataType    | string       | text    | 请求完成后,要对返回数据解析的类型,可以是`json`(数组),`jsonObject`,`xml`,`query`,`serialize`和`text`
referer     | string       | 无      | 请求HTTP头中的referer值
userAgent   | string       | 无      | 请求HTTP头中的userAgent值
beforeSend  | callback     | 无      | 在发送请求前触发的回调,可通过该回调更改任意配置
success     | callback     | 无      | 请求并解析数据成功后触发的回调
error       | callback     | 无      | 请求或解析失败后触发的回调,可用于记录日志,展示错误信息等
complete    | callback     | 无      | 请求完成后,不论是否成功都触发的回调,可用于数据记录等

### 回调

#### beforeSend($call, $ch)

名称        | 类型         | 说明
------------|--------------|------
$call       | Widget\Call  | 当前的Call对象
$ch         | resource     | cUrl会话的变量

#### success($data, $call)

名称        | 类型         | 说明
------------|--------------|------
$data       | mixed        | 解析过的HTTP响应数据,数据类型与`dataType`选项相关,如下表所示
$call       | \Widget\Call | 当前的Call对象

##### `dataType`的值与$data返回的类型

dataType   | 返回数据类型
-----------|--------------
json       | array
jsonObject | stdClass
xml        | SimpleXMLElement
query      | array
serialize  | 任意PHP合法类型
text       | string

#### error($call, $textStatus, $exception)

名称        | 类型         | 说明
------------|--------------|------
$call       | Widget\Call  | 当前的Call对象
$textStatus | string       | 简单的错误说明,可能出现的值及出现情况如下表
$exception  | Exception    | 错误的异常对象,可通过该对象获取详细的错误信息

##### `$textStatus`的值与出现的情况

值          | 出现情况
------------|--------------
curl        | cURL内部错误,如无法解析域名IP地址
http        | HTTP状态码错误,如404页面不存在,500内部错误
parser      | 数据解析错误,如返回的数据不是正确的json格式

#### complete($call, $ch)

名称        | 类型         | 说明
------------|--------------|------
$call       | Widget\Call  | 当前的Call对象
$ch         | resource     | cUrl会话的变量

### 方法

#### call($options = array())
初始化一个新的Call对象,并执行HTTP请求

#### call($url, $options = array())
根据给定的URL地址,初始化一个Call对象,并执行HTTP请求

#### call->isSuccess()
返回请求及数据解析是否成功

返回: `bool`

#### call->getCh()
获取cURL的资源变量

#### call->getResponseHeader($name)
获取HTTP响应头的值

#### call->getResponseText()
获取HTTP响应的文本数据

#### call->setMethod($method)
设置HTTP的请求方式

#### call->setRequestHeader($name, $value)
设置HTTP请求头的值

#### call->success($callback)
设置请求并解析数据成功后触发的回调

#### call->error($callback)
设置请求或解析失败后触发的回调

#### call->complete($callback)
设置请求完成后触发的回调

#### call->get($url, $data, $callback, $dataType)
通过GET方式发送带数据的请求,成功时触发`$callback`回调

#### call->get($url, $callback, $dataType)
通过GET方式发送请求,成功时触发`$callback`回调

#### call->getJson($url, $callback)
通过GET方式发送请求,并以JSON格式解析返回数据,成功时触发`$callback`回调

#### call->post($url, $data, $callback, $dataType)
通过POST方式发送带数据的请求,成功时触发`$callback`回调

#### call->post($url, $callback, $dataType)
通过POST方式发送请求,成功时触发`$callback`回调

#### call->put($url, $data, $callback, $dataType)
通过PUT方式发送带数据的请求,成功时触发`$callback`回调

#### call->put($url, $callback, $dataType)
通过PUT方式发送请求,成功时触发`$callback`回调

#### call->delete($url, $data, $callback, $dataType)
通过DELETE方式发送带数据的请求,成功时触发`$callback`回调

#### call->delete($url, $callback, $dataType)
通过DELETE方式发送请求,成功时触发`$callback`回调

#### call->patch($url, $data, $callback, $dataType)
通过PATCH方式发送带数据的请求,成功时触发`$callback`回调

#### call->patch($url, $callback, $dataType)
通过PATCH方式发送请求,成功时触发`$callback`回调