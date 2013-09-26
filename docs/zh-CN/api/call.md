Call
====

象jQuery Ajax一样调用您的接口

案例
----

### 一个完整的例子:获取公开的gists列表

```php
$call = widget()->call(array(
    // 设置请求的URL地址
    'url' => 'https://api.github.com/gists',
    // 默认请求方式为GET,可以设置为POST,PUT等
    'method' => 'GET',
    // 自动解析返回数据为JSON数组
    'dataType' => 'json',
    // 设置发送的参数
    'data' => array(
        'time' => time(),
    ),
    'beforeSend' => function($call, $ch) {
        // 在发送前额外设置cURL选项
        //curl_setopt($ch, 'xx', 'xx');
    }
));

if ($call->isSuccess()) {
    // 请求成功,获取解析后的返回值
    $response = $call->getResponse();

    // 输出第一条的地址,如https://api.github.com/gists/xxxxxxx
    var_dump($response[0]['url']);
} else {
    // 输出错误的类型,如`curl`,完全错误类型请查看选项
    var_dump($call->getErrorStatus());

    // 输出异常的信息,如`Couldn't resolve host '404.php.net'`
    var_dump($call->getErrorException()->getMessage());
}
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
$call = widget()->call(array(
    'url' => 'http://google.com',
    // 随机使用其中一个IP地址
    'ip' => array_rand(array(
        '74.125.128.147',
        '74.125.128.105'
    ))
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
widget()->call->get('http://example.com', function($data, $call){
    // 成功时打印出服务器返回的结果
    print_r($data);
});
```

### 通过回调方法记录请求日志

Call对象提供了`beforeSend`, `success`, `error`, `complete`四个回调方法,可以用来记录日志,上报请求耗时等.

```php
wei(array(
    'call' => array(
        // cURL请求发送前的回调,记录请求的方式和地址参数
        'beforeSend' => function (\Widget\Call $call, $ch) {
            wei()->logger->debug(array(
                'Request URL'       => $call->getUrl(),
                'Request Method'    => $call->getMethod(),
                'Parameters'        => $call->getData(),
            ));
        },
        // cURL请求发送成功的回调
        'success' => function ($data, \Widget\Call $call) {
            // 按需记录
        },
        // cURL请求发送失败的回调,记录错误原因和异常堆栈
        'error' => function (\Widget\Call $call) {
            wei()->logger->error(array(
                'Error status'  => $call->getErrorStatus(),
                'Exception'     => (string)$call->getErrorException(),
            ));
        },
        // cURL请求发送完成的回调,记录返回状态码,花费时间等
        'complete' => function (\Widget\Call $call, $ch) {
            $curlInfo = curl_getinfo($ch);

            wei()->logger->debug(array(
                'Status Code' => $curlInfo['http_code'],
                'Server IP'     => $call->getIp() ?: '(Not specified)',
                'Total Time'    => $curlInfo['total_time'] . 's',
                'Response Body' => $call->getResponse(),
            ));
        }
    )
));
```

### 禁用SSL证书验证

在开发环境中,如果我们未安装SSL证书,访问HTTPS网站会提示如下错误.

>    curl SSL certificate problem, verify that the CA cert is OK. Details: error:14090086:SSL routines:SSL3_GET_SERVER_CERTIFICATE:certificate verify failed

我们可以设置cURL的`CURLOPT_SSL_VERIFYPEER`选项来禁用SSL证书验证.当然该方法不建议在正式环境使用.

```php
widget()->call(array(
    'url' => 'https://api.github.com/gists',
    'curlOptions' => array(
        CURLOPT_SSL_VERIFYPEER => false
    )
));
```

扩展阅读:

在PHP中使用cURL来访问受SSL/TLS保护的网站

http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/

StackOverflow上关于该问题的讨论和解决方法

http://stackoverflow.com/questions/6400300/php-curl-https-causing-exception-ssl-certificate-problem-verify-that-the-ca-cer

调用方式
--------

### 选项

名称        | 类型         | 默认值  | 说明
------------|--------------|---------|------
method      | string       | GET     | HTTP的请求方式,可以是`GET`, `POST`, `DELETE`, `PUT`, `PATCH`或任意其他服务器支持的方式
contentType | string       | application/x-www-form-urlencoded; charset=UTF-8 | HTTP请求头内容类型
cookies     | array        | array() | cookie数组,key是cookie的名称,value是cookie的值,cookie的值只能是字符串
data        | array,string | array() | 要发送到服务器的数据
global      | bool         | true    | 是否使用全局配置选项
headers     | array        | array   | 要发送的HTTP头
ip          | string       | 无      | 要请求的URL地址中域名的IP地址,注意不是您的服务器IP地址
timeout     | int          | 0       | 整个请求的最大运行时间,单位是毫秒,默认是无限制
dataType    | string       | text    | 请求完成后,要对返回数据解析的类型,可以是`json`(数组),`jsonObject`,`xml`,`query`,`serialize`和`text`
referer     | string       | 无      | 请求HTTP头中的referer值
userAgent   | string       | 无      | 请求HTTP头中的userAgent值
beforeSend  | callable     | 无      | 在发送请求前触发的回调,可通过该回调更改任意配置
success     | callable     | 无      | 请求并解析数据成功后触发的回调
error       | callable     | 无      | 请求或解析失败后触发的回调,可用于记录日志,展示错误信息等
complete    | callable     | 无      | 请求完成后,不论是否成功都触发的回调,可用于数据记录等

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

#### call->get($url, $callback = null, $dataType = null)
通过GET方式发送请求,成功时触发`$callback`回调

#### call->get($url, $data = array(), $callback = null, $dataType = null)
通过GET方式发送带数据的请求,成功时触发`$callback`回调

#### call->getJson($url, $callback = null)
通过GET方式发送请求,并以JSON格式解析返回数据,成功时触发`$callback`回调

#### call->getJson($url, $data = array(), $callback = null)
通过GET方式发送带数据的请求,成功时触发`$callback`回调

#### call->post($url, $data = array(), $callback = null, $dataType = null)
通过POST方式发送带数据的请求,成功时触发`$callback`回调

#### call->post($url, $callback = null, $dataType = null)
通过POST方式发送请求,成功时触发`$callback`回调

#### call->put($url, $data = array(), $callback = null, $dataType = null)
通过PUT方式发送带数据的请求,成功时触发`$callback`回调

#### call->put($url, $callback = null, $dataType = null)
通过PUT方式发送请求,成功时触发`$callback`回调

#### call->delete($url, $data = array(), $callback = null, $dataType = null)
通过DELETE方式发送带数据的请求,成功时触发`$callback`回调

#### call->delete($url, $callback = null, $dataType = null)
通过DELETE方式发送请求,成功时触发`$callback`回调

#### call->patch($url, $data = array(), $callback = null, $dataType = null)
通过PATCH方式发送带数据的请求,成功时触发`$callback`回调

#### call->patch($url, $callback = null, $dataType = null)
通过PATCH方式发送请求,成功时触发`$callback`回调